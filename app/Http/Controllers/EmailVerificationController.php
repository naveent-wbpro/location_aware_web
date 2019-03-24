<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;

class EmailVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['company'] = \App\Company::where('id', '=', \Auth::user()->company_id)->with('allNetworks')->first();
        $data['user'] = \Auth::user();

        echo view('email_verification.index', $data)->render();
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
        $user = \Auth::user();
        $user->email_verification_token = str_random(50);
        $user->save();

        Mail::send('emails.email_verification', ['user' => $user], function ($m) use ($user) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user->email, $user->name)->subject('Email Verification for LocationAware');
        });

        return redirect('/email_verification')->with('success', 'Verification Email has been sent.');
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
    public function update(Request $request)
    {
        //$user = \Auth::user();
        //Naveen Added code to verify email
        $user = \App\User::where('email_verification_token', '=', $request->input('token'))->first();

        if ($request->input('token') === $user->email_verification_token) {
            $user->email_verified = true;
            $user->save();

            return redirect('/')->with('success', 'Email has been verified');
        } else {
            return redirect('/email_verification')->with('error', 'Email token could not be verified');
        }
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
