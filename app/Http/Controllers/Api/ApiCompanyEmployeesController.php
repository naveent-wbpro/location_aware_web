<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Hash;
use Mail;

class ApiCompanyEmployeesController extends Controller
{
    public function index($id, Request $request)
    {
        $company =  \App\Company::where('id', '=', $id)->first();

        echo $company->employees;
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
            'name' => 'required',
            'email' => 'required|unique:users|email',
        ]);

        $company = \App\Company::find($id);

        $new_password = strtolower(str_random(10));
        $user = new \App\User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($new_password);
        $user->verify_token = str_random(50);
        $user->role_id = 4;
        $user->company_id = $id;
        $user->save();


        Mail::send('emails.account_creation_notification', ['user' => $user, 'company' => $company, 'new_password' => $new_password], function ($m) use ($user) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user->email, $user->name)->subject('Your LocationAware account has been created');
        });

        echo $user;
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
