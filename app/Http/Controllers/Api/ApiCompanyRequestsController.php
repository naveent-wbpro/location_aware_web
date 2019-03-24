<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class ApiCompanyRequestsController extends Controller
{
    public function index($id, Request $request)
    {
        $company =  \App\Company::where('id', '=', $id)->first();

        if ($request->get('unclaimed') == 'true') {
            echo $company->unclaimedRequests;
        } else {
            echo $company->requests;
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
    public function store($id, Request $request)
    {
        $this->validate($request, [
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone_number' => 'required|max:255',
            'customer_address' => 'required|max:255',
            'customer_zipcode' => 'required|max:255',
        ]);

        $data = $request->input();
        $request = new \App\Request();
        $request->company_id = $id;
        $request->fill($data);
        return $request->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
