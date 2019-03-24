<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyNetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $id)->with('allNetworks')->first();
        $data['networks'] = \App\Network::where('company_id', '=', $id)->with('owner', 'companies')->first();

        return view('companies.networks.index', $data)->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $id)->first();
        $data['network'] = new \App\Network();

        return view('companies.networks.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $new_network = new \App\Network();
        $new_network->name = $request->input('name');
        $new_network->description = $request->input('description');
        $new_network->company_id = $id;
        $new_network->save();

        $company = \App\Company::where('id', '=', $id)->first();

        $company->allNetworks()->attach($new_network->id);

        return redirect('/companies/'.$id.'/networks');
    }

    /**
     * Display the specified resource.
     *
     * @param int $company_id
     * @param int $network_id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($company_id, $network_id)
    {
        $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $company_id)->first();
        $data['network'] = \App\Network::where('id', '=', $network_id)->first();
        $data['is_network_owner'] = $data['network']->company_id == $company_id;
        $data['pending_invitations'] = \App\NetworkInvitation::where('invited_by_company_id', '=', $company_id)
            ->where('created_at', '>', \Carbon\Carbon::now()->modify('-3 days'))
            ->where('network_id', '=', $network_id)
            ->get();

        return view('companies.networks.show', $data)->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id, $network_id)
    {
        $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $company_id)->first();
        $data['network'] = \App\Network::where('id', '=', $network_id)->where('company_id', '=', $company_id)->first();

        return view('companies.networks.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $network_id)
    {
        //
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $new_network = \App\Network::where('id', '=', $network_id)->first();
        $new_network->name = $request->input('name');
        $new_network->description = $request->input('description');
        $new_network->save();

        return redirect('/companies/'.$id.'/networks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $network_id)
    {
        $network = \App\Network::where('id', '=', $network_id)->where('company_id', '=', $company_id)->first();
        if (count($network) == 1) {
            $network->delete();
            $code_snippet = \App\CodeSnippetWebsite::where('network_id', '=', $network_id)->update(['network_id' => null]);
        }

        return redirect('/companies/'.$company_id.'/networks');
    }


    /**
     * Shows map for network
     * @param int $company_id
     * @param int $network_id
     *
     * @return \Illuminate\Http\Response
     */
    public function map($company_id, $network_id)
    {
        $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $company_id)->first();
        $data['network'] = \App\Network::where('id', '=', $network_id)->first();
        $data['is_network_owner'] = $data['network']->company_id == $company_id;
        $data['pending_invitations'] = \App\NetworkInvitation::where('invited_by_company_id', '=', $company_id)
            ->where('created_at', '>', \Carbon\Carbon::now()->modify('-3 days'))
            ->where('network_id', '=', $network_id)
            ->get();

        return view('companies.networks.map', $data)->render();
    }

    /**
     * Gets employees from network location
     * @param int $company_id
     * @param int $network_id
     *
     * @return \Illuminate\Http\Response
     */
    public function locations($company_id, $network_id)
    {
        $network = \App\Network::where('id', '=', $network_id)->first();

        echo $network->employeeLocations;
    }
}
