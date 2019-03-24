<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

class AdminCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['companies'] = \App\Company::orderBy('name', 'asc')->with('employees')->get();

        return view('admin.companies.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['company'] = new \App\Company();

        return view('admin.companies.create', $data);
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|max:255',
        ]);

        $company = new \App\Company();
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->is_visible = !empty($request->input('is_visible')) ? $request->input('is_visible') : 0;
        $company->save();

        return redirect('admin/companies');
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
        $data['company'] = \App\Company::where('id', '=', $id)->with('employees', 'allNetworks')->first();

        return view('admin.companies.edit', $data);
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
        $company = \App\Company::where('id', '=', $id)->first();
        $company->name = $request->input('name');
        $company->is_visible = !empty($request->input('is_visible')) ? 1 : null;
        $company->iicrc_certified = !empty($request->input('iicrc_certified')) ? \Carbon\Carbon::now() : null;
        $company->credentialed = !empty($request->input('credentialed')) ? \Carbon\Carbon::now() : null;
        $company->save();

        return redirect('admin/companies');
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
