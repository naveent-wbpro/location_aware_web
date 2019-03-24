<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;

class AccountSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user'] = \Auth::user();
        $data['company'] = \Auth::user()->company;
        $data['timezones'] = \App\Libraries\Timezone::america();

        return view('account_settings.index', $data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,'.\Auth::user()->id.'|email',
        ]);

        if (!empty($request->input('password')) && !empty($request->input('password_confirmation'))) {
            $this->validate($request, [
                'password' => 'required|min:4|confirmed',
                'password_confirmation' => 'required',
            ]);
        }

        \Auth::user()->timezone = $request->input('timezone');

        \Auth::user()->email = $request->input('email');
        \Auth::user()->name = $request->input('name');

        if (!empty($request->input('password')) && !empty($request->input('password_confirmation'))) {
            \Auth::user()->password = Hash::make($request->input('password'));
        }

        \Auth::user()->save();

        return redirect()->action('AccountSettingsController@index')->with('success', 'Profile updated!');
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
