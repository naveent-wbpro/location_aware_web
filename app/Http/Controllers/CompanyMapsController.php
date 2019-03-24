<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyMapsController extends Controller
{

    /**
     * Display a map of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id)
    {
        //
    $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $company_id)->with('employees')->first();
        $data['employees'] = $data['company']->employees;

        return view('companies/maps/index', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employee_locations($id = null, Request $request)
    {
        $company = \App\Company::where('id', '=', $id)->firstOrFail();
        echo $company->employeeLocations;
    }

    public function office_locations($id, Request $request)
    {
        $company = \App\Company::where('id', '=', $id)->firstOrFail();
        echo $company->officeLocations;
    }
}
