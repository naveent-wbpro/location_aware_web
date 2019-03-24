<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Http\Request;
use App\Http\Requests;
use Stripe\Error\Card;
use Mail;

/**
 * Class CompanyRequestsController
 *
 * @package App\Http\Controllers
 */
class CompanyRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data['company'] = \App\Company::where('id', '=', $id)->with('allNetworks')->first();
        $data['user'] = \Auth::user();
        $network_ids = $data['company']->allNetworks->pluck('id')->all();

        $data['network_requests'] = \App\Request::whereIn('network_id', $network_ids)
                                                    ->whereNotNull('instant_response')
                                                    ->whereNull('closed_on')
                                                    ->whereNull('claimed_on')
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();

        $data['company_requests'] = \App\Request::where('company_id', '=', $id)
            ->where(function ($query) {
                $query->whereNull('network_id')
                    ->orWhere('network_id', '=', 0)
                    ->orWhereNotNull('employee_id')
                    ->orWhereNotNull('office_id');
            })
            ->whereNull('closed_on')
            ->orderBy('created_at', 'desc');


        /**
         *
         */
        if (\Auth::user()->role_id === 4) {
            $request_ids = \Auth::user()->requests->pluck('id');
            $data['company_requests'] = $data['company_requests']->whereIn('id', $request_ids);
        }

        $data['company_requests'] = $data['company_requests']->get();

        return view('companies.requests.index', $data)->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $data['code_snippet'] = \App\CodeSnippetWebsite::where('api_key', '=', $request->input('key'))->with('forms.fields')->first();
        $data['instant_response'] = $request->input('instant_response');
        $data['company_generated'] = $request->input('company_generated');
        $data['forms'] = [];

        if (!empty($data['code_snippet'])) {
            $data['forms'] = $data['code_snippet']->forms;
        }

        if($data['company_generated'] == true) {
            $data['forms'] = \App\Form::where('company_id', '=', \Auth::user()->company_id)->where('code_snippet_website_id', '=', 0)->get();
        }

        if ($request->input('office_id') != null) {
            $data['office'] = \App\CompanyOffice::where('id', '=', $request->input('office_id'))->first();
            $data['company'] = $data['office']->company;
        } elseif ($request->input('employee_id') != null) {
            $data['employee'] = \App\User::where('id', '=', $request->input('employee_id'))->first();
            $data['company'] = $data['employee']->company;
        } else {
            $data['company'] = \App\Company::where('id', '=', $id)->first();
        }

        if ($request->input('prime_id') && $request->input('prime_id') != 'null') {
            $prime_claim = \App\PrimeClaim::where('id', '=', $request->input('prime_id'))->where('created_at', '>', \Carbon\Carbon::now()->subHour())->first();

            if (!empty($prime_claim)) {
                $prime_claim->displayed_at = \Carbon\Carbon::now();
                $prime_claim->save();

                $data['name'] = $prime_claim->insured;
                $data['phone_number'] = $prime_claim->insured_phone;
                $data['street_address'] = $prime_claim->street_address_1 . ' ' . $prime_claim->city;
                $data['zipcode'] = $prime_claim->zipcode;
                $data['email'] = $prime_claim->insured_email;
            }
        } else {
            $data['name'] = null;
            $data['phone_number'] = null;
            $data['street_address'] = null;
            $data['zipcode'] = null;
            $data['email'] = null;
        }

        $data['network_id'] = $request->input('network_id');
        $data['time_windows'] = \App\TimeWindow::pluck('description', 'id')->all();
        $data['street_address'] = !isset($data['street_address']) ? $request->input('street_address') : $data['street_address'];
        $data['zipcode'] = !isset($data['zipcode']) ? $request->input('zipcode') : $data['zipcode'];

        return view('companies.requests.create', $data)->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (\App::environment() !== 'local') {
            $recaptcha = new \ReCaptcha\ReCaptcha('6LdqBEEUAAAAAGEVoQ-jlhXqV9hJbMar0-Gn9fdK');
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), @$_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                $errors = $resp->getErrorCodes();
                return redirect(app(UrlGenerator::class)->previous())
                    ->withErrors($errors)
                    ->withInput($request->input());
            }
        }

        $this->validate($request, [
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone_number' => 'required|max:255',
            'customer_phone_number_type' => 'required|max:255',
            'customer_address' => 'required|max:255',
            'customer_zipcode' => 'required|max:255',
        ]);

        $data = $request->input();

        $coords = \App\Libraries\Coordinates::streetAddress($data['customer_address'] . ' ' . $data['customer_zipcode']);
        $latitude = @$coords->latitude;
        $longitude = @$coords->longitude;


        if (empty($latitude) || empty($longitude)) {
            return redirect(app(UrlGenerator::class)->previous())
                ->withErrors(['Address could not be found.'])
                ->withInput($request->input());
        }

        $new_request = new \App\Request();
        $new_request->company_id = $id;
        $new_request->fill($data);
        if (empty($data['scheduled_date'])) {
            $new_request->scheduled_date = null;
        }
        if (isset($data['network_id']) && $data['network_id'] == 0) {
            $new_request->network_id = null;
        }
        if (isset($data['instant_response'])) {
            $new_request->instant_response = 1;
        }
        if (isset($data['company_generated']) && $data['company_generated'] == true) {
            if (\Auth::user()->company_id == $new_request->company_id) {
                $new_request->company_generated_user_id = \Auth::user()->id;
                $new_request->claimed_on = \Carbon\Carbon::now();
            } else {
                return redirect(app(UrlGenerator::class)->previous())
                    ->withErrors(['You must be logged in as a company admin to create company generated new_requests.'])
                    ->withInput($request->input());
            }
        }

        $new_request->save();

        if (isset($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $key => $value) {
                $custom_field = new \App\CustomResponse();
                $custom_field->form_field_id = $key;
                $custom_field->response = $value;
                $custom_field->request_id = $new_request->id;
                $custom_field->save();
            }
        }

        return redirect('/companies/'.$new_request->company_id.'/requests/'.$new_request->id.'/validation_choices');

    }

    /**
     * @param $company_id
     * @param $request_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validationChoices($company_id, $request_id)
    {
        $new_request = \App\Request::find($request_id);

        return view('companies.requests.validation_choices', ['request' => $new_request]);
    }

    /**
     * @param Request $request
     */
    public function confirmViaPhone(Request $request, $company_id, $request_id)
    {
        $job_request = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $request_id)->first();
        $job_request->validation_string = mt_rand(100000, 999999);
        $job_request->save();

        $params = [
            'region' => 'us-east-1', // < your aws from SNS Topic region
            'version' => 'latest'
        ];
        $sns = new \Aws\Sns\SnsClient($params);

        $args =[
            "SenderID" => "Location Aware",
            "SMSType" => "Transactional",
            "Message" => "Your LocationAware code is: ".$job_request->validation_string,
            "PhoneNumber" => "+1".$job_request->raw_customer_phone_number
        ];

        $result = $sns->publish($args);

        return view('companies.requests.confirmation_sent', compact('job_request'));
    }

    /**
     * @param Request $request
     */
    public function confirmViaEmail(Request $request, $company_id, $request_id)
    {
        $job_request = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $request_id)->first();
        $job_request->validation_string = mt_rand(100000, 999999);
        $job_request->save();

        \Mail::send('emails.confirm_your_email_for_request', ['request' => $job_request], function ($m) use ($job_request) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($job_request->customer_email)->subject('Confirm Your Email');
        });

        return view('companies.requests.confirmation_sent', compact('job_request'));
    }

    /**
     * @param Request $request
     */
    public function validateRequest(Request $request, $company_id, $request_id)
    {
       $job_request = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $request_id)->first(); 

       if ($job_request->validation_string !== $request->input('validation_string')) {
           return redirect('/companies/'.$company_id.'/requests/'.$request_id.'/validation_choices')
               ->withErrors(['The code you inputed as incorrect'])
               ->withInput($request->input());
       }

       $job_request->validated_at = \Carbon\Carbon::now();
       $job_request->save();

        \Mail::send('emails.request_received', ['request' => $job_request], function ($m) use ($job_request) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($job_request->customer_email)->subject('Your Request');
        });

        if ($job_request->instant_response == 1) {
            // Search for radius
            if ($job_request->network_id !== null) {
                // Search for companies in network
                $network = \App\Network::where('id', '=', $job_request->network_id)->first();
                $unfiltered_companies = $network->companies;
            } else {
                // get company
                $unfiltered_companies = \App\Company::where('id', '=', $job_request->company_id)->get();
            }

            $companies = $unfiltered_companies->filter(function($value, $key) use($job_request) {
                return $value->hasUserAvailableNear($job_request->latitude, $job_request->longitude);
            });

            if ($companies->isEmpty()) {
                return redirect(app(UrlGenerator::class)->previous())
                    ->withErrors(['Unfortunately, there is no service provider within 40 miles of your location.'])
                    ->withInput($job_request);
            }
        } else {
            $companies = collect([$job_request->company]);
        }

        foreach ($companies as $company) {
            $company->requests()->attach($job_request->id);
            foreach ($company->admins as $admin) {
                \Mail::send('emails.new_request_for_company',['admin' => $admin, 'request' => $job_request, 'company' => $company], function ($m) use ($job_request, $company, $admin) {
                    $m->from('support@locationaware.io', 'Location Aware');
                    $m->to($admin->email)->subject('New request for your company.');
                });
            }
        }

        if ($job_request->company_generated_user_id !== null) {
            return redirect('/companies/'.\Auth::user()->company_id.'/requests');
        } else {
            return view('companies.requests.store', ['request' => $job_request]);
        }
    }

    /**
     * Claim a specific resource to a company.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $company_id
     * @param int $request_id
     * @return \Illuminate\Http\Response
     */
    public function claim(Request $request, $company_id, $request_id)
    {
        $price = \App\Price::where('item', '=', 'claim')->first();
        $company = \App\Company::where('id', '=', $company_id)->first();
        $owner = $company->owner;

        if ($price->stripeAmount != 0 && ($owner->card_last_four == null || $owner->card_brand == null)) {
            return redirect()->route('companies.{company_id}.requests.show', [$company_id, $request_id])->with('error', "No payment source available. Please add a payment source");
        }

        $job = \App\Request::where('id', '=', $request_id)->first();
        $is_instant_response = $job->instant_response;

        if ($price->stripeAmount == 0) {
            $job->claimed_on = \Carbon\Carbon::now();
            $job->company_id = $company->id;
            $job->save();

            return redirect('companies/' . $company_id . '/requests/' . $request_id . '/edit');
        } else {
            try {
                $payment = $owner->charge($price->stripeAmount);
                $job->claimed_on = \Carbon\Carbon::now();
                $job->company_id = $company->id;
                $job->save();

                return redirect('companies/' . $company_id . '/requests/' . $request_id . '/edit');
            } catch (Card $e) {
                $job->claimed_on = null;
                $job->save();

                Rollbar::report_exception($e);
                return redirect()->route('companies.{company_id}.requests.show', [$company_id, $request_id])->with('error', "There was an error processing your payment.");
            } catch (InvalidRequest $e) {
                $job->claimed_on = null;
                $job->save();

                Rollbar::report_exception($e);
                return redirect()->route('companies.{company_id}.requests.show', [$company_id, $request_id])->with('error', "There was an error processing your payment.");
            } catch (\Exception $e) {
                $job->claimed_on = null;
                $job->save();

                return redirect()->route('companies.{company_id}.requests.show', [$company_id, $request_id])->with('error', "There was an error processing your payment. Reason: " . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $id, Request $request)
    {
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['request'] = \App\Request::where('id', '=', $id)->first();
        $data['price'] = \App\Price::where('item', '=', 'claim')->first();

        if ($data['request'] != null) {
            if ($data['request']->claimed_on != null) {
                return redirect('companies/'.$company_id.'/requests/'.$id.'/edit');
            }

            return view('companies.requests.show', $data)->render();
        }
        return redirect()->route('companies.{company_id}.requests.index', [$company_id])->with('error', 'Request not found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id, $id)
    {
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['request'] = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $id)->with('employees')->first();

        return view('companies.requests.edit', $data)->render();
    }

    /**
     * Show the form for assigning employees to a request.
     *
     * @param int $company_id
     * @param int $request_id
     *
     * @return \Illuminate\Http\Response
     */
    public function assignments($company_id, $request_id)
    {
        $data['company'] = \App\Company::where('id', '=', $company_id)->with('employees')->first();
        $data['user'] = \Auth::user();
        $data['request'] = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $request_id)->with('employees')->first();

        return view('companies.requests.assignments', $data)->render();
    }

    /**
     * update the assignments employees to a request.
     *
     * @param int $company_id
     * @param int $request_id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAssignments(Request $request, $company_id, $request_id)
    {
        $users = \App\User::whereIn('id', $request->input('employees'))->get();
        $request = \App\Request::where('id', '=', $request_id)->first();

        if (count($users) > 0) {
            $request->employees()->sync($users->pluck('id')->all());
            $request->assigned_on = \Carbon\Carbon::now();
            $request->save();

            $hash_id = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
            $hashed_id = $hash_id->encode($request->id);

            $params = [
                'region' => 'us-east-1', // < your aws from SNS Topic region
                'version' => 'latest'
            ];

            if ($request->customer_phone_number_type == 'Cell') {
                $sns = new \Aws\Sns\SnsClient($params);

                $args =[
                    "SenderID" => "Location Aware",
                    "SMSType" => "Transactional",
                    "Message" => $request->company->name." has assigned a service worker to your request. Track your service worker here: ". str_replace('https://', '', secure_url("requests/".$hashed_id)),
                    "PhoneNumber" => "+1".$request->raw_customer_phone_number
                ];

                $result = $sns->publish($args);
            }

            foreach ($users as $user) {
                $hash_id = new \Hashids\Hashids();
                $hashed_id = $hash_id->encode($request_id);
                \Mail::send('emails.employee_assigned', compact('user', 'request', 'hashed_id'), function ($m) use ($request, $user, $hashed_id) {
                    $m->from('support@locationaware.io', 'Location Aware');
                    $m->to($user->email)->subject('New assignment');
                });

                if ($user->phone != null) {
                    $sns = new \Aws\Sns\SnsClient($params);

                    $args = array(
                        "SenderID" => "Location Aware",
                        "SMSType" => "Transactional",
                        "Message" => "You have been assigned a new request. ".str_replace('https://', '', secure_url("companies/{$request->company_id}/requests/{$request->id}/acknowledge?user_id={$user->id}")) ,
                        "PhoneNumber" => "+1".$user->raw_phone
                    );

                    $result = $sns->publish($args);
                }
            }

            // return redirect()->route('companies.{company_id}.requests.edit', [$company_id, $request_id]);
            return redirect()->route('requests.edit', [$company_id, $request_id]);
        } else {
            return redirect()->route('companies.{company_id}.requests.show', [$company_id, $request_id])->with('error', 'No employee selected to assign the request to.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id      company id
     * @param int                      $id      request_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id, $request_id)
    {
        $this->validate($request, [
            'request_status' => 'required',
        ]);

        $user_request = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $request_id)->first();

        if ($request->input('request_status') == 'contacted_on') {
            $user_request->contacted_on = \Carbon\Carbon::now();
        }
        if ($request->input('request_status') == 'assigned_on') {
            $user_request->assigned_on = \Carbon\Carbon::now();
        }
        if ($request->input('request_status') == 'arrived_on') {
            $user_request->arrived_on = \Carbon\Carbon::now();
        }
        if ($request->input('request_status') == 'closed_on') {
            $user_request->closed_on = \Carbon\Carbon::now();
        }
        $user_request->save();

        return redirect()->route('requests.edit', [$company_id, $request_id]);
    }

    /**
     * @param Request $request
     * @param $company_id
     * @param $request_id
     *
     * @return \Illuminate\Http\Response
     */
    public function acknowledge(Request $request, $company_id, $request_id)
    {
        $user_request = \App\RequestUser::where('request_id', '=', $request_id)
            ->where('user_id', '=', $request->get('user_id'))
            ->firstOrFail();

        $user_request->acknowledged_at = \Carbon\Carbon::now();
        $user_request->save();

        $data['request'] = \App\Request::find($request_id);
        $data['user'] = $user_request->user;

        \Mail::send('emails.employee_assigned_to_customer', $data , function ($m) use ($data) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($data['request']->customer_email)->subject('Your Service Worker');
        });

        \Mail::send('emails.employee_directions', $data, function ($m) use ($user_request) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user_request->user->email)->subject('Directions to your service location');
        });

        echo view('companies/requests/acknowledge', $data);
    }

    /**
     * @param Request $request
     * @param $company_id
     * @param $request_id
     *
     * @return \Illuminate\Http\Response
     */
    public function onTheWay(Request $request, $company_id, $request_id)
    {
        $user_request = \App\RequestUser::where('request_id', '=', $request_id)
            ->where('user_id', '=', $request->get('user_id'))
            ->firstOrFail();

        $la_request = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $request_id)->first();

        $user_request->on_the_way_at = \Carbon\Carbon::now();
        $user_request->save();

        $data['request'] = \App\Request::find($request_id);
        $data['user'] = $user_request->user;

        $hash_id = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
        $data['hashed_id'] = $hash_id->encode($data['request']->id);

        \Mail::send('emails.employee_on_the_way', $data , function ($m) use ($data) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($data['request']->customer_email)->subject('Your Service Worker');
        });

        echo view('companies/requests/employee_directions', $data)->render();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
