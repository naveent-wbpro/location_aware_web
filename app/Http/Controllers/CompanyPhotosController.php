<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;

class CompanyPhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        /** @var \App\Company $data['company'] */
        $data['can_edit'] = \Auth::check() && \Auth::user()->company_id == $id;
        $data['company'] = \App\Company::find($id);
        $data['code_snippet'] = \App\CodeSnippetWebsite::where('company_id', '=', $id)->where('id', '=', $data['company']->code_snippet_website_id)->first();
        $data['photos'] = $data['company']->photos;

        echo view('companies.photos.index', $data)->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $data['can_edit'] = \Auth::check() && \Auth::user()->company_id == $id;
        $data['company'] = \App\Company::find($id);
        $data['code_snippet'] = \App\CodeSnippetWebsite::where('company_id', '=', $id)->where('id', '=', $data['company']->code_snippet_website_id)->first();
        $data['photos'] = $data['company']->photos;

        echo view('companies.photos.create', $data)->render();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company_id)
    {
        $this->validate($request, [
            'file' => 'image'
        ]);

        $destination_path = storage_path().'/files';
        $file = $request->file('photo');
        $filename = md5(time().$file->getClientOriginalName()).'.'.$file->guessExtension();
        $file->move($destination_path, $filename);
        $s3 = Storage::put($filename, file_get_contents($destination_path.'/'.$filename), 'public');

        $new_photo = new \App\Photo();
        $new_photo->file_key = $filename;
        $new_photo->url = 'https://s3.amazonaws.com/locationaware/'.$filename;
        $new_photo->save();

        $company = \App\Company::where('id', '=', $company_id)->firstOrFail();
        $company->photos()->attach([$new_photo->id]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $id)
    {
        if(\Auth::check() && \Auth::user()->company_id == $company_id) {
            $photo = \App\Photo::find($id);
            $company = \App\Company::find($company_id);

            Storage::delete($photo->file_key);

            $company->photos()->detach([$photo->id]);
            $photo->delete();
        }

        return redirect('/companies/'.$company_id.'/photos/create');
    }
}
