<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyHistoricalRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data['company'] = \App\Company::where('id', '=', $id)->first();
        $data['user'] = \Auth::user();
        $data['company_requests'] = \App\Request::where('company_id', '=', $id)->whereNotNull('closed_on')->orderBy('closed_on', 'desc')->get();

        return view('companies.requests.history', $data)->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['company'] = \App\Company::where('id', '=', $id)->first();
        $data['user'] = \Auth::user();
        $data['employees'] = $data['company']->employees;

        return view('companies.requests.create_history', $data)->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_address' => 'required',
            'customer_city' => 'required',
            'customer_state' => 'required',
            'customer_zipcode' => 'required'
        ]);

        $job = new \App\Request();
        $job->company_id = $id;
        $job->customer_name = $request->input('customer_name');
        $job->customer_phone_number = $request->input('customer_phone_number');
        $job->customer_email = $request->input('customer_email');
        $job->customer_address = $request->input('customer_address');
        $job->customer_city = $request->input('customer_city');
        $job->customer_state = $request->input('customer_state');
        $job->customer_zipcode = $request->input('customer_zipcode');
        $job->created_at = \Carbon\Carbon::parse($request->input('date'));
        $job->arrived_on = \Carbon\Carbon::parse($request->input('date'));
        $job->claimed_on = \Carbon\Carbon::parse($request->input('date'));
        $job->departed_on = \Carbon\Carbon::parse($request->input('date'));
        $job->contacted_on = \Carbon\Carbon::parse($request->input('date'));
        $job->arrived_on = \Carbon\Carbon::parse($request->input('date'));
        $job->arrived_on_user_id = \Auth::user()->id;
        $job->closed_on = \Carbon\Carbon::parse($request->input('date'));
        $job->save();
        
        return redirect('companies/'.$id.'/requests/history');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resendSurvey($company_id, $id)
    {
        $job_request = \App\Request::where('company_id', '=', $company_id)->where('id', '=', $id)->first();

        if ($job_request !== null && $job_request->survey_result === null) {
            $job_request->touch();
        }

        return redirect('companies/'.$id.'/requests/history');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
