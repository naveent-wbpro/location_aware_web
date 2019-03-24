<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

class CompanyNetworkCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Displays a search response to a company.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $company_id, $network_id)
    {
        $this->validate($request, [
            'search_term' => 'required|min:3|max:255',
        ]);

        $data['companies'] = \App\Company::where('name', 'like', '%'.$request->input('search_term').'%')->get();
        $data['company_id'] = $company_id;
        $data['network_id'] = $network_id;

        return view('companies.networks._search_result', $data)->render();
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
    public function store(Request $request, $company_id, $network_id)
    {
        //
        $companies = \App\Company::with('admins')->whereIn('id', $request->input('company_ids'))->get();

        foreach ($companies as $invited_company) {
            if (!empty($invited_company)) {
                \App\NetworkInvitation::where('company_id', '=', $invited_company->id)->where('invited_by_company_id', '=', $company_id)->where('network_id', '=', $network_id)->delete();

                $invite = new \App\NetworkInvitation();
                $invite->company_id = $invited_company->id;
                $invite->invited_by_company_id = $company_id;
                $invite->network_id = $network_id;
                $invite->accept_token = str_random(25);
                $invite->save();

                foreach($invited_company->admins as $admin) {
                    Mail::send('emails.network_invitation', ['user' => $admin, 'company' => $invited_company], function ($m) use ($admin) {
                        $m->from('support@locationaware.io', 'Location Aware');
                        $m->to($admin->email, $admin->name)->subject('Your company has been invited to a new network');
                    });
   
                }
            }
        }

        return redirect('/companies/'.$company_id.'/networks/'.$network_id);
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
    public function update(Request $request, $company_id, $network_id, $invitation_id)
    {
        //
         $invitation = \App\NetworkInvitation::where('company_id', '=', $company_id)->where('network_id', '=', $network_id)->where('id', '=', $invitation_id)->first();

        if ($request->input('decision') == 'accept') {
            $company = \App\Company::where('id', '=', $company_id)->first();
            $company->allNetworks()->attach($invitation->network_id);
        }
        $invitation->delete();

        return redirect('/companies/'.$company_id.'/networks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $network_id, $network_company_id)
    {
        $network = \App\Network::where('id', '=', $network_id)->first();
        if ($network->company_id == $company_id && $network_company_id != $company_id) {
            $network_company = \App\Company::where('id', '=', $network_company_id)->first();
            $network_company->allNetworks()->detach($network_id);
        }

        return redirect('/companies/'.$company_id.'/networks/'.$network_id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function leave($company_id, $network_id)
    {
        $company = \App\Company::where('id', '=', $company_id)->first();
        $network = \App\Network::where('id', '=', $network_id)->first();
        if ($company->belongsToNetwork($network_id)) {
            $company->allNetworks()->detach($network_id);
        }

        return redirect('/companies/'.$company_id.'/networks');
    }
}
