<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Mail;

class CustomerThermometerWebhooksController extends Controller
{
    public function store()
    {
        $data = file_get_contents('php://input');

        $thermometer = json_decode($data);

        $request = \App\Request::where('id', '=', $thermometer->custom_2)->where('company_id', '=', $thermometer->custom_1)->firstOrFail();
        $request->survey_result = $thermometer->temperature_id;
        $request->survey_comment = $thermometer->comment;
        $request->surveyed_at = \Carbon\Carbon::now();
        $request->save();

        /** @var \App\User $admin */
        foreach ($request->company->admins as $admin) {
            \Mail::send('emails.survey_received', compact('request', 'admin'), function ($m) use ($request, $admin) {
                $m->from('support@locationaware.io', 'Location Aware');
                $m->to($admin->email)->subject('New Survey Response');
            });
        }
    }
}
