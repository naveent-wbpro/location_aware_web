<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
/**
 * Class CompanyEmployeeLocationsController
 * @package App\Http\Controllers
 */
class CompanyEmployeeLocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null, Request $request)
    {
        $origin_url = $request->server('HTTP_ORIGIN');
        $code_key = \App\CodeSnippetWebsite::where('company_id', '=', $id)->where('api_key', '=', $request->input('api_key'))->first();

        if ((auth()->check()) || (count($code_key) == 1)) {
            #&& parse_url($code_key->url)['host'] == parse_url($origin_url)['host'])) {
            if (count($code_key) == 1 && $code_key->network_id != 0) {
                $network = \App\Network::where('id', '=', $code_key->network_id)->firstOrFail();
                return $network->employeeLocations;
            } else {
                $company = \App\Company::where('id', '=', $id)->firstOrFail();
                return $company->employeeLocations;
            }
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset(getallheaders()['user_id'], getallheaders()['access_token'])) {
            $user_id = getallheaders()['user_id'];
            $access_token = getallheaders()['access_token'];
        } else {
            $user_id = $request->header('user_id');
            $access_token = $request->header('access_token');
        }

        $app_token = \App\AppToken::where('user_id', '=', $user_id)->where('access_token', '=', $access_token)->first();


        if (!empty($app_token)) {
            if (isset($request->input()[0], $request->input()[0]['latitude'])) {
                $employee_location = new \App\Location();
                $employee_location->user_id = $app_token->user_id;
                $employee_location->latitude = $request->input()[0]['latitude'];
                $employee_location->longitude = $request->input()[0]['longitude'];
                $employee_location->save();
            } else {
                $employee_location = new \App\Location();
                $employee_location->user_id = $app_token->user_id;
                $employee_location->latitude = $request->input('latitude');
                $employee_location->longitude = $request->input('longitude');
                $employee_location->save();
            }
            return json_encode($employee_location);
        } else {
            abort(401);
        }
    }

    /**
     *
     */
    public function getHistory()
    {
        Log::debug("Inside CompanyEmployeeLocationsController::getHistory::");
        $user_id = getallheaders()['user_id'];
        $access_token = getallheaders()['access_token'];
        Log::debug("userId is".$user_id);
        $app_token = \App\AppToken::where('user_id', '=', $user_id)->where('access_token', '=', $access_token)->first();

        if (!empty($app_token)) {
            $history = \App\Location::where('user_id', '=', $user_id)
                                    ->orderBy('id', 'desc')
                                    ->groupBy(\DB::raw('MINUTE(created_at)'))
                                    ->where('created_at', '>', \Carbon\Carbon::now()->subHour())
                                    ->limit(30)
                                    ->get();
            echo $history;
        } else {
            abort(401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        $data['company'] = \App\Company::find($id);
        return view('companies.code_snippet.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
