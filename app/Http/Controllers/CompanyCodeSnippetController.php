<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyCodeSnippetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id)
    {
        //
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['networks_array'] = $data['company']->ownedNetworks->pluck('name', 'id');

        return view('companies.code_snippet.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company_id)
    {
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['code_snippet'] = new \App\CodeSnippetWebsite();

        return view('companies.code_snippet.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($company_id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'url' => 'required',
        ]);
        $code_snippet = new \App\CodeSnippetWebsite();
        $code_snippet->company_id = $company_id;
        $code_snippet->name = $request->input('name');
        $code_snippet->url = $request->input('url');
        $code_snippet->api_key = str_random(25);
        $code_snippet->save();

        if ($request->input('public_url') === '1') {
            $hashids = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
            $code_snippet->public_url = $hashids->encode($code_snippet->id);
            $code_snippet->save();
        }

        return redirect()->route('code_snippet.index', $company_id)->withFlash('success', 'New website validated');
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
    public function edit($company_id, $id)
    {
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['code_snippet'] = \App\CodeSnippetWebsite::find($id);

        return view('companies.code_snippet.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id, $snippet_id)
    {
        $snippet = \App\CodeSnippetWebsite::where('company_id', '=', $company_id)->where('id', '=', $snippet_id)->first();
        if (!empty($snippet)) {
            if ($request->input('url') && $request->input('name')) {
                $snippet->url = $request->input('url');
                $snippet->name = $request->input('name');

                if ($request->input('public_url') === '1') {
                    $hashids = new \Hashids\Hashids('AMx05k50Tyx676FWdNcSThcr4ZpDf7FR');
                    $snippet->public_url = $hashids->encode($snippet->id);
                } else {
                    $snippet->public_url = null;  
                }
            } else {
                $snippet->network_id = ($request->input('network_id') == 0 ? null: $request->input('network_id'));
            }

            $snippet->save();

            if ($request->ajax()) {
                return $snippet;
            } else {
                return redirect('companies/'.$company_id.'/code_snippet')->with('success', 'Snippet updated');
            }
        } else {
            if ($request->ajax()) {
                abort(422);
            } else {
                $this->edit($company_id, $snippet_id);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $website_id)
    {
        $website = \App\CodeSnippetWebsite::where('company_id', '=', $company_id)->where('id', '=', $website_id)->first();

        if (!empty($website)) {
            $website->delete();
            session()->flash('success', 'Website has been revoked!');
        } else {
            session()->flash('error', 'Error revoking website!');
        }

        return redirect()->route('code_snippet.index', $company_id);
    }
}
