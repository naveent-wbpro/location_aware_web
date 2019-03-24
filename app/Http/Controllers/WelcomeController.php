<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;

/**
 * Class WelcomeController
 * @package App\Http\Controllers
 */
class WelcomeController extends Controller
{
    //
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        if (\Auth::check() && \Auth::user()->role_id == 1) {
            return redirect('/admin');
        }

        if (\Auth::check() && \Auth::user()->role_id == 4) {
            return redirect('/companies/'.\Auth::user()->company_id.'/requests');
        }

        if (\Auth::check()) {
            return redirect('/companies/'.\Auth::user()->company_id.'/requests');
        }

        $data['ads'] = \App\Ad::whereNotNull('posted_at')->get();
        return view('welcome', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function contactUs(Request $request)
    {
        Mail::send('emails.new_contact_form_request', ['request' => $request], function ($m) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to('warrenerickson@outlook.com', 'Warren Erickson')->subject('New Location Aware Request');
        });

        Mail::send('emails.new_request_confirm', ['request' => $request], function ($m) use ($request) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($request->email, $request->name)->subject("We've received your request");
        });

        return redirect('/');
    }
}
