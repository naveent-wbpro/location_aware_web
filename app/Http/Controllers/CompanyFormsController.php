<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CompanyFormsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id)
    {
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['forms'] = \App\Form::with('fields')->where('company_id', '=', $company_id)->get();
        $data['code_snippets'] = \App\CodeSnippetWebsite::where('company_id', '=', $company_id)->pluck('name', 'id')->all();

        echo view('companies.forms.index', $data)->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company_id)
    {
        //
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['form'] = new \App\Form();

        echo view('companies.forms.create', $data);
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
            'form_name' => 'required'
        ]);

        $form = new \App\Form();
        $form->company_id = $company_id;
        $form->name = $request->input('form_name');
        $form->save();

        foreach ($request->input('field_name') as $key => $name) {
            if ($name != '') {
                $form_field = new \App\FormField();
                $form_field->form_id = $form->id;
                $form_field->name = $name;
                $form_field->input_type = $request->input('field_type')[$key];
                $form_field->save();
            }
        }

        $this->index($company_id);
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
    public function edit($company_id, $id)
    {
        //
        $data['company'] = \App\Company::find($company_id);
        $data['user'] = \Auth::user();
        $data['form'] = \App\Form::where('company_id', '=', $company_id)->where('id', '=', $id)->with('fields')->first();

        echo view('companies.forms.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id, $form_id)
    {
        $this->validate($request, [
            'form_name' => 'required'
        ]);

        $form = \App\Form::where('company_id', '=', $company_id)->where('id', '=', $form_id)->first();
        $form->name = $request->input('form_name');
        $form->save();

        $current_keys = array_keys($request->input('field_name'));

        \App\FormField::where('form_id', '=', $form_id)->where('form_id', '=', $form->id)->whereNotIn('id', $current_keys)->delete();

        foreach ($request->input('field_name') as $key => $name) {
            if ($name != '') {
                $form_field = new \App\FormField();
                if ($key !== 0) {
                    $form_field = \App\FormField::firstOrNew(['id' => $key]);
                } 
                $form_field->form_id = $form->id;
                $form_field->name = $name;
                $form_field->input_type = $request->input('field_type')[$key];
                $form_field->save();
            }
        }

        $this->index($company_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCodeSnippets(Request $request, $company_id)
    {
        $form = \App\Form::where('company_id', '=', $company_id)->where('id', '=', $request->input('form_id'))->first();;
        $form->code_snippet_website_id = ($request->input('code_snippet_website_id') === '' ? null : $request->input('code_snippet_website_id'));
        $form->save();

        echo $form;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $form_id)
    {
        \App\Form::where('company_id', '=', $company_id)->where('id', '=', $form_id)->delete();

        return redirect('/companies/'.$company_id.'/forms');
    }
}
