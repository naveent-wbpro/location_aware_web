<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use PDF;

class CompanyReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id)
    {
        $data['user'] = \Auth::user();
        $data['company'] = \Auth::user()->company;

        echo view('companies.reports.index', $data)->render();
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
    public function show($company_id, $user_id, Request $request)
    {

        $from_date = date("m/d/Y", strtotime(\Carbon\Carbon::now()->subWeek()));
        $to_date   = date("m/d/Y", strtotime(\Carbon\Carbon::now()));

        if ((strlen($request->input('from_date')) == 10) && (strlen($request->input('to_date')) == 10)) {
            $from_date =  date("Y/m/d", strtotime($request->input('from_date')));
            $to_date   =  date("Y/m/d", strtotime($request->input('to_date')));
        } 
        /** @var \App\User */
        $data['user'] = \App\User::where('id', '=', $user_id)->where('company_id', '=', $company_id)->firstOrFail();
   
        $data['assignments'] = $data['user']->assignmentsBetween($from_date, $to_date)->get();
     
        $data['company'] = \Auth::user()->company;

        $data['validate'] = array(
          'last_week'=> date("m/d/Y", strtotime(\Carbon\Carbon::now()->subWeek())),

          'last_week_start'=> date("m/d/Y",strtotime("last sunday midnight",strtotime("-1 week +1 day"))),
          'last_week_end'=> date("m/d/Y",strtotime("next saturday",strtotime("last sunday midnight",strtotime("-1 week +1 day")))),

          'last_month_start'=> date("m/d/Y",strtotime(new \Carbon\Carbon('first day of previous month'))),
          'last_month_end'=> date("m/d/Y",strtotime(new \Carbon\Carbon('last day of previous month'))),
          'last_quarter_start'=> date('m/d/Y', strtotime("-3 months", strtotime(date("m/d/Y", strtotime(\Carbon\Carbon::now()->firstOfQuarter()))))),
          'last_quarter_end'=> date('m/d/Y', strtotime("-3 months", strtotime(date("m/d/Y", strtotime(\Carbon\Carbon::now()->lastOfQuarter()))))),
          'year_to_date'=> date("m/d/Y",strtotime(new \Carbon\Carbon('first day of January this year'))),

          'last_year_start'=> date("m/d/Y", strtotime(new \Carbon\Carbon('first day of January last year'))),
          'last_year_end'=> date("m/d/Y", strtotime(new \Carbon\Carbon('last day of December last year'))),
          'last_year'=> date("m/d/Y", strtotime(\Carbon\Carbon::now()->subYear())),
          'current_month_start'=> date("m/d/Y",strtotime(new \Carbon\Carbon('first day of this month'))),
          'current_month'=> date("m/d/Y",  strtotime(\Carbon\Carbon::now()->subMonth())),
          'to_date' =>   date("m/d/Y", strtotime(\Carbon\Carbon::now()))
        );

         $data['search'] = array(
          'from_date'=>  $from_date,
          'to_date'=> $to_date
          );


        if ($request->has('csv')) {

            $filename = time().".csv";
            $handle = fopen(storage_path().'/'.$filename, 'w+');
            fputcsv($handle, array('Date', 'Time', 'Total Time(Hrs)', 'Distance Travelled','Cusatomer Name','Insurance Carrier','Job Type'));

            foreach ($data['assignments'] as $assignment) {
                $time = 'Start:'.$assignment['contacted_on']."\n".
                        'Arrive:'.$assignment['arrived_on']."\n".
                        'End  :'.$assignment['closed_on'];

                $time_travelled =  \Auth::user()->totalActiveGpsTimeRequest($assignment['contacted_on'], $assignment['closed_on'], $user_id);

                $distance_travelled = number_format(\Auth::user()->totalDistanceTravelledByrequest($assignment['contacted_on'], $assignment['closed_on'], $user_id));


                  
                fputcsv($handle, array($assignment['claimed_on'],$time, $time_travelled,$distance_travelled,$assignment['customer_name'], '-', 'New Assignments'));
            }

            fclose($handle);
            $headers = array(
                    'Content-Type' => 'text/csv',
                );
            return \Response::download(storage_path().'/'.$filename,  $filename.'.csv', $headers);
        } elseif ($request->has('pdf')) {
             view()->share('assignments',$data);
             $pdf = PDF::loadView('companies.reports.pdfview')->setPaper('a4','landscape');
             return $pdf->download(time().'.pdf');

        }else {
             return view('companies.reports.show', $data)->render();
        }
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
     /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_request_type(Request $request)
    {
      //$data = $request->all(); 
      $request_id  = $request->input('request_id');  
      $job_type    = $request->input('job_type');  
      $requests    = \App\Request::where('id', '=', $request_id)->first();
      $requests->type = $job_type;
      $requests->save();

    }

}
