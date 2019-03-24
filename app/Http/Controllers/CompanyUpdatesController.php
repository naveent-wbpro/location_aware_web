<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyUpdatesController extends Controller
{
    public function index($id)
    {
        $company = \App\Company::where('id', '=', $id)->with('allNetworks')->first();
        $network_ids = $company->allNetworks->pluck('id')->all();

        $network_requests = \App\Request::whereIn('network_id', $network_ids)
            ->whereNotNull('instant_response')
            ->whereNull('closed_on')
            ->whereNull('claimed_on')
            ->whereNull('assigned_on')
            ->orderBy('created_at', 'desc')
            ->get();


        $company_requests = \App\Request::where('company_id', '=', $company->id)
            ->where(function ($query) {
                $query->whereNull('network_id')
                    ->orWhere('network_id', '=', 0)
                    ->orWhereNotNull('employee_id')
                    ->orWhereNotNull('office_id');
            })
            ->whereNull('closed_on')
            ->whereNull('assigned_on')
            ->orderBy('created_at', 'desc')
            ->get();

        echo json_encode(['network_requests' => $network_requests, 'company_requests' => $company_requests]);
    }
}
