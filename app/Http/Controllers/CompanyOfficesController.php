<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use CountryState;

class CompanyOfficesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data['company'] = \App\Company::where('id', '=', $id)->with('offices')->first();
        $data['user'] = \Auth::user();

        return view('companies.offices.index', $data)->render();
    }

    /**
     * Gets locations of company offices
     *
     * @return json
     */
    public function getLocations($id, Request $request)
    {
        Log::debug("The request params are".$request->input('api_key'));
        $origin_url = $request->server('HTTP_REFERER');
        $code_key = \App\CodeSnippetWebsite::where('company_id', '=', $id)->where('api_key', '=', $request->input('api_key'))->first();

        if ((auth()->check()) || count($code_key) == 1) {
            if ($code_key->network_id != 0) {
                $network = \App\Network::where('id', '=', $code_key->network_id)->firstOrFail();
                echo $network->officeLocations;
            } else {
                $company = \App\Company::where('id', '=', $id)->firstOrFail();
                echo $company->officeLocations;
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
    public function create($id)
    {
        $data['company'] = \App\Company::where('id', '=', $id)->with('offices')->first();
        $data['user'] = \Auth::user();
        $data['states'] = CountryState::getStates('US');
        $data['states'] += CountryState::getStates('CA');
        $data['office'] = new \App\CompanyOffice();

        return view('companies.offices.create', $data)->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        //
        $this->validate($request, [
            'street'   => 'required',
            'state'    => 'required',
            'zip_code' => 'required'
        ]);

        $new_office = new \App\CompanyOffice();
        $new_office->company_id = $id;
        $new_office->name = $request->input('name');
        $new_office->street = $request->input('street');
        $new_office->city = $request->input('city');
        $new_office->state = $request->input('state');
        $new_office->zip_code = $request->input('zip_code');


        $url_encoded_address = urlencode($new_office->street . ' ' . $new_office->city . ' ' . $new_office->zip_code . ' ' . $new_office->state);
        $url = 'https://maps.google.com/maps/api/geocode/json?sensor=false&key=AIzaSyAKTYbVY7GJCRt5gfKf0oHtu28x_zRr8so&address='.$url_encoded_address;
        $response = file_get_contents($url);
        Log::debug('store::The response from Google API is' .$response);
        $json = json_decode($response, true);

        if (!empty($json) && !empty($json['results']) && $json['status'] !== "ZERO_RESULTS") {
            $new_office->latitude = $json['results'][0]['geometry']['location']['lat'];
            $new_office->longitude = $json['results'][0]['geometry']['location']['lng'];

            $url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.$new_office->latitude.','.$new_office->longitude.'&timestamp='.time().'&key=AIzaSyAKTYbVY7GJCRt5gfKf0oHtu28x_zRr8so';
            $timezone = file_get_contents($url);
            if (!empty(json_decode($timezone))) {
                $new_office->timezone = json_decode($timezone)->timeZoneId;
            }
        }

        $new_office->save();

        return redirect('/companies/'.$id.'/offices');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $company_id
     * @param  int  $office_id
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id, $office_id)
    {
        $data['company'] = \App\Company::where('id', '=', $company_id)->with('offices')->first();
        $data['user'] = \Auth::user();
        $countryState = new CountryState;
        $data['states'] = CountryState::getStates('US');
        $data['states'] += CountryState::getStates('CA');
        $data['office'] = \App\CompanyOffice::where('id', '=', $office_id)->first();

        return view('companies/offices/edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id, $office_id)
    {
        $this->validate($request, [
            'street'   => 'required',
            'state'    => 'required',
            'zip_code' => 'required'
        ]);

        $new_office = \App\CompanyOffice::where('id', '=', $office_id)->first();
        $new_office->company_id = $company_id;
        $new_office->name = $request->input('name');
        $new_office->street = $request->input('street');
        $new_office->city = $request->input('city');
        $new_office->state = $request->input('state');
        $new_office->zip_code = $request->input('zip_code');

        $url_encoded_address = urlencode($new_office->street . ' ' . $new_office->city . ' ' . $new_office->zip_code . ' ' . $new_office->state);
        $url = 'https://maps.google.com/maps/api/geocode/json?sensor=false&key=AIzaSyAKTYbVY7GJCRt5gfKf0oHtu28x_zRr8so&address='.$url_encoded_address;
        $response = file_get_contents($url);
        Log::debug('The response from Google API is' .$response);
        $json = json_decode($response, true);

        if (!empty($json) && !empty($json['results']) && $json['status'] !== "ZERO_RESULTS") {
            $new_office->latitude = $json['results'][0]['geometry']['location']['lat'];
            $new_office->longitude = $json['results'][0]['geometry']['location']['lng'];

            $url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.$new_office->latitude.','.$new_office->longitude.'&timestamp='.time().'&key=AIzaSyAKTYbVY7GJCRt5gfKf0oHtu28x_zRr8so';
            $timezone = file_get_contents($url);
            if (!empty(json_decode($timezone))) {
                $new_office->timezone = json_decode($timezone)->timeZoneId;
            }
        }

        $new_office->save();

        return redirect('/companies/'.$company_id.'/offices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $office_id)
    {
        \App\CompanyOffice::where('company_id', '=', $id)->where('id', '=', $office_id)->delete();

        return redirect('companies/'.$id.'/offices');
    }
}
