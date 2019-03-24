<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param hash_id $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hashids = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
        $id = $hashids->decode($id)[0];

        $data['request'] = \App\Request::where('id', '=', $id)->whereNull('departed_on')->whereNull('closed_on')->first();

        if ($data['request'] !==  null) {
            return view('requests.show', $data)->render();
        } else {
            return redirect('/404');
        }
    }


    public function locations($id)
    {
        $hashids = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
        $id = $hashids->decode($id)[0];

        $request = \App\Request::findOrFail($id);

        echo json_encode($request->employeeLocations());
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
}
