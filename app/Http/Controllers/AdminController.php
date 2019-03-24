<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    //
    //
    public function index()
    {
        $data['companies_count'] = \App\Company::count();
        $data['users_count'] = \App\User::count();
        $data['new_users'] = \App\User::where('created_at', '>', \Carbon\Carbon::now(0)->subMonth())->count();
        $data['open_requests'] = \App\Request::whereNull('closed_on')->count();
        $data['active_mobile_units'] = \App\Location::groupBy('user_id')->where('created_at', '>', \Carbon\Carbon::now()->subHours(24))->count();

        return view('admin.index', $data);
    }
}
