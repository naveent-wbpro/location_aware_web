<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        // Do something here.
        $data['trades'] = \App\Trade::pluck('name', 'id');

        return view('auth.register', $data);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required',
            'trade_id' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $company = Company::create([
            'name' => $data['company_name'],
            'trade_id' => $data['trade_id'],
            'email' => $data['email']
        ]);

        /**
         * Auto connect to networks
         */
        /*
        if (app()->environment('production')) {
            $trade = \App\Trade::find($data['trade_id']);
            $network = \App\Network::where('name', '=', $trade->name)->first();

            if ($network !== null) {
                $network->companies()->attach($company->id);
            }
        }
         */

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company_id' => $company->id,
            'email_verification_token' => str_random(50),
            'role_id' => 2,
            'password' => bcrypt($data['password']),
        ]);

        Mail::send('emails.email_verification', ['user' => $user], function ($m) use ($user) {
            $m->from('support@locationaware.io', 'Location Aware');
            $m->to($user->email, $user->name)->subject('Email Verification for LocationAware');
        });

        return $user;        
    }
}
