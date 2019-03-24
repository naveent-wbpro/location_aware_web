<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Libraries\Icalendar;
use Calendar;

class CompanyMonitoringController extends Controller
{
    public function index($company_id, Request $request)
    {
        $events           = array();
        $data['user']     = \Auth::user();
        $data['company']  = \Auth::user()->company;
        $closed_on =  date("Y-m-d", strtotime("-4 day"));
				
		$data['requests'] = \App\Request::where('company_id', '=', $company_id)
                           ->where('closed_on', '>', $closed_on)
                           ->orderBy('closed_on', 'desc')->get(); 					

        foreach ($data['requests'] as $requests) {
            $class = 'purple';
     
            $events[] = Calendar::event($requests->customer_name, 
                false,
                new \DateTime(date('Y-m-d H:i:s', strtotime($requests->created_at))),
                new \DateTime(date('Y-m-d H:i:s', strtotime($requests->created_at))),
                $requests->id,
                [
                    'url' => '#',
                    'className' => $class,
                    'request_id'=>$requests->id
                ]);

           
        }

        $calendar = Calendar::addEvents($events);
      /*  $calendar = Calendar::setCallbacks([
                'eventClick' => 'function(calEvent, jsEvent, view) {
                    $(\'#myModal\').modal(\'toggle\');
                }',
            ]);
	  */	
        $data['calendar'] = $calendar;
        return view('companies.monitoring.index', $data)->render();
        
    }
}
