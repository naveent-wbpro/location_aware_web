<?php

namespace App\Http\Controllers;

use Storage;
use Mail;
use File;
use Hash;
use Illuminate\Http\Request;

class CompanyEmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id, Request $request)
    {
        //
        $data['user'] = \Auth::user();
        $data['company'] = \App\Company::where('id', '=', $company_id)->with('employees')->first();
        $data['employees'] = $data['company']->employees;

        if ($request->input('sort') != null) {
            $data['employees'] = $data['employees']->sortBy($request->input('sort'));
        }

        return view('companies/employees/index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company_id)
    {
        //
        $data['company'] = \App\Company::where('id', '=', $company_id)->with('employees')->first();
        $data['user'] = new \App\User();
        $data['trades'] = \App\Trade::pluck('name', 'id');

        return view('companies/employees/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company_id)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|email',
        ]);

        $company = \App\Company::find($company_id);

        $new_password = strtolower(str_random(10));

        $user = new \App\User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($new_password);
        $user->phone = $request->input('phone');
        $user->verify_token = str_random(50);
        $user->role_id =  $request->input('role_id');
        $user->company_id = $company->id;
        $user->trade_id = $request->input('trade_id');
        $user->description = $request->input('description');
        $user->save();


        Mail::send('emails.account_creation_notification', ['user' => $user, 'company' => $company, 'new_password' => $new_password], function ($m) use ($user) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user->email, $user->name)->subject('Your LocationAware account has been created');
        });

        return redirect('/companies/'.$company_id.'/employees');
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id, $employee_id)
    {
        //
        $data['company'] = \App\Company::where('id', '=', $company_id)->first();
        $data['user'] = \Auth::user();
        $data['employee'] = \App\User::where('id', '=', $employee_id)->first();
        $data['offices'] = $data['company']->offices->pluck('name', 'id')->all();
        $data['trades'] = \App\Trade::pluck('name', 'id');

        return view('companies.employees.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id, $employee_id)
    {
        //
        $user = \App\User::where('id', '=', $employee_id)->first();
        if ($request->input('role_id')) {
            $user->role_id = $request->input('role_id');
        }
        $user->company_office_id = $request->input('office_id');
        $user->phone = $request->input('phone');
        $user->trade_id = $request->input('trade_id');
        $user->description = $request->input('description');
        $user->employee_since = \Carbon\Carbon::parse($request->input('employee_since'));
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

            $user->profile_photo_id = $new_photo->id;
            File::delete($destination_path.'/'.$filename);
        }

        $user->save();

        return redirect('/companies/'.$company_id.'/employees');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $user_id)
    {
        \App\User::where('id', '=', $user_id)->delete();

        return redirect()->action('CompanyEmployeesController@index', ['company_id' => $company_id])->with('success', 'User has been deleted');
    }
}
