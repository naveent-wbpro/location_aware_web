<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CountryState;
use File;
use DB;
use Storage;
use Hash;
use App\Http\Requests;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['states'] = CountryState::getStates('US');
        $data['trades'] = \App\Trade::pluck('name', 'id');

        if ($request->input('name') || $request->input('trade_id') || $request->input('state')) {
            $query = \App\Company::where('is_visible', '=', 1)->whereNotNull('credentialed')->orderBy('name', 'asc');
            $query = empty($request->input('trade_id')) ? $query : $query->where('trade_id', '=', $request->input('trade_id'));
            $query = empty($request->input('name')) ? $query : $query->where('name', 'like', '%'.$request->input('name').'%');
            $query = empty($request->input('state')) ? $query : $query->where('state', '=', $request->input('state'));

            $data['companies'] = $query->paginate(15)->appends($request->except(['page', '_token']));

            $data['state'] = $request->input('state');
            $data['name'] = $request->input('name');
            $data['trade_id'] = $request->input('trade_id');

            if ($request->input('trade_id')) {
                $selected_trade = \App\Trade::find($request->input('trade_id'));
                $data['code_snippet_data'] = \App\CodeSnippetWebsite::where('company_id', '=', 32)->where('name', '=', $selected_trade->name)->first();
            }
        } else {
            $data['state'] = null;
            $data['trade_id'] = null;
            $data['companies']= \App\Company::where('is_visible', '=', 1)->whereNotNull('credentialed')->orderBy('name', 'asc')->paginate(15);
        }

        echo view('companies.index', $data)->render();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function compare(Request $request)
    {
        $data['companies'] = \App\Company::whereIn('id', $request->input())->with('closedRequests')->get();

        echo view('companies.compare', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var \App\Company $data['company'] */
        $data['can_edit'] = \Auth::check() && \Auth::user()->company_id == $id;
        $data['company'] = \App\Company::find($id);
        $data['code_snippet'] = \App\CodeSnippetWebsite::where('company_id', '=', $id)->where('id', '=', $data['company']->code_snippet_website_id)->first();
        $data['closed_requests'] = $data['company']->closedRequests->count();
        $data['ratings'] = $data['company']->requestsWithReview;
        $data['average_rating'] = $data['company']->getAverageRating();
        $data['show_map'] = true;

        echo view('companies.reviews.index', $data)->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['company'] = \App\Company::find($id);
        $data['trades'] = \App\Trade::pluck('name', 'id');
        $data['available_maps'] = \App\CodeSnippetWebsite::where('company_id', '=', $id)->whereNotNull('public_url')->pluck('name', 'id');
        $data['states'] = CountryState::getStates('US');

        echo view('companies.edit', $data)->render();
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
        $this->validate($request, [
            'description' => 'required',
            'trade_id' => 'required'
        ]);

        $company = \App\Company::find($id);
        $company->description = $request->input('description');
        $company->trade_id = $request->input('trade_id');
        $company->email = $request->input('email');
        $company->website = $request->input('website');
        $company->street_address = $request->input('street_address');
        $company->zip_code = $request->input('zip_code');
        $company->city = $request->input('city');
        $company->state = $request->input('state');
        $company->phone_number = $request->input('phone_number');
        $company->years_in_business = $request->input('years_in_business');
        $company->code_snippet_website_id = $request->input('code_snippet_website_id');
        $company->contractor_warranty = $request->input('contractor_warranty') === '1' ? \Carbon\Carbon::now() : null;

        if ($request->hasFile('photo')) {
            $destination_path = storage_path().'/files';
            $file = $request->file('photo');
            $filename = md5(time().$file->getClientOriginalName()).'.'.$file->guessExtension();
            $file->move($destination_path, $filename);
            $s3 = Storage::put($filename, file_get_contents($destination_path.'/'.$filename), 'public');
            
            $new_photo = new \App\Photo();
            $new_photo->file_key = $filename;
            $new_photo->url = 'https://s3.amazonaws.com/locationaware/'.$filename;
            $new_photo->save();

            $company->photo_id = $new_photo->id;
            File::delete($destination_path.'/'.$filename);

        }

        $company->save();

        return redirect('/companies/'.$id);
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
