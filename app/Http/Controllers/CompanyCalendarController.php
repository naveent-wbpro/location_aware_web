<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\View;
use App\Libraries\Icalendar;
use Calendar;


class CompanyCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id, Request $request)
    {
        $events           = array();
        $data['user']     = \Auth::user();
        $data['company']  = \Auth::user()->company;
        $data['validate'] = $request->input('selected_employee');

        /** Default to employee if employee is logged in **/
        if (\Auth::user()->role_id === 4) {
            $selected_employee = \Auth::user()->id;
        } else {
            $selected_employee = $request->input('selected_employee');
            $data['employees'] = \Auth::user()->company->employees;
        }

        if (!empty($selected_employee)) {
            $data['requests'] = \App\Request::select('requests.*')
                            ->join('request_user', 'requests.id', '=', 'request_user.request_id')
                            ->where('requests.company_id', '=', $company_id)
                            ->where('request_user.user_id', '=', $selected_employee)
                            ->orderBy('closed_on', 'desc')->get();  
        } else {
            $data['requests'] = \App\Request::where('company_id', '=', $company_id)
                            ->where('company_id', '=', $company_id)
                            ->orderBy('closed_on', 'desc')->get(); 
        }
  
        foreach ($data['requests'] as $job_request) {
            $start_time = $job_request->getScheduledDateTime(\Auth::user()->timezone);
            $end_time = $start_time;
            $events[] = Calendar::event($job_request->customer_name, 
                false,
                $start_time,
                $end_time,
                $job_request->id,
                [
                    'url' => '/companies/'.$company_id.'/requests/'.$job_request->id,
                    'request_id'=>$job_request->id
                ]);
           
        }

        $data['calendar'] = Calendar::addEvents($events);

        if ($request->input('popup')) {
            return view('companies.calendar.popup', $data)->render();
        } else {
            return view('companies.calendar.index', $data)->render();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    public function download()
    {
        $event            = new Icalendar();
        $data['requests'] = \App\Request::whereNotNull('closed_on')->orderBy('closed_on', 'desc')->get();
        foreach ($data['requests'] as $requests) {
            $requests->customer_name;
            $requests->claimed_on;
            $requests->closed_on;
            $event->add($requests->claimed_on, $requests->closed_on, $requests->customer_name, $requests->customer_name, "GU1 1AA");
        }
        $data = $event->save();
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename=' . time() . '.ics');
        echo $data;
        exit;
    }
}
