<?php

namespace App\Http\Controllers;

use Hash;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class AdminUsersController
 * @package App\Http\Controllers
 */
class AdminUsersController extends Controller
{
    /**
     *
     */
    public function index()
    {
        \Auth::user()->companies;
        $data['users'] = \App\User::orderBy('name', 'asc')->with('company', 'role')->get();

        return view('admin.users.index', $data)->render();
    }

    /**
     *
     */
    public function create()
    {
        $data['user'] = new \App\User();
        $data['roles'] = \App\Role::pluck('name', 'id');
        $data['companies'] = \App\Company::pluck('name', 'id');

        return view('admin.users.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'company_id' => 'required_if:role_id,2'
        ]);


        $new_password = strtolower(str_random(10));

        $user = new \App\User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->input('role_id') == 1) {
            $user->company_id = 0;
        } else {
            $user->company_id = $request->input('company_id');
        }

        $user->role_id = $request->input('role_id');
        $user->password = Hash::make($new_password);
        $user->verify_token = str_random(50);
        $user->save();

        Mail::send('emails.account_creation_notification', ['user' => $user, 'company' => $user->company, 'new_password' => $new_password], function ($m) use ($user) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user->email, $user->name)->subject('Your LocationAware account has been created');
        });

        return redirect('/admin/users');
    }

    /**
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,'.$id,
            'company_id' => 'required_if:role_id,2'
        ]);

        $user = \App\User::find($id);
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');

        if ($request->input('role_id') != 1) {
            $user->company_id = $request->input('company_id');
        } else {
            $user->company_id = null;
        }

        $user->role_id = $request->input('role_id');
        $user->save();

        return redirect('/admin/users');
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        $data['user'] = \App\User::find($id);
        $data['roles'] = \App\Role::pluck('name', 'id');
        $data['companies'] = \App\Company::pluck('name', 'id');

        return view('admin.users.edit', $data)->render();
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function destroy(Request $request, $id)
    {
        $user = \App\User::where('id', '=', $id)->forceDelete();

        return $this->index();
    }


    /**
     * @param $id
     */
    public function reset_password($id)
    {
        $user = \App\User::find($id);
        $new_password = strtolower(str_random(10));
        $user->password = Hash::make($new_password);
        $user->verify_token = str_random(50);
        $user->save();

        Mail::send('emails.account_creation_notification', ['user' => $user, 'company' => $user->company, 'new_password' => $new_password], function ($m) use ($user) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user->email, $user->name)->subject('Your LocationAware account has been created');
        });

        return redirect('admin/users')->with('success', 'Password for '.$user->name.' reset');
    }
}
