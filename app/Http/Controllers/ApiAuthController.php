<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiAuthController extends Controller
{
    //
    public function login(Request $request)
    {
        Log::debug ("Inside the login method with request");
        Log::debug("The username is".$request->input('email'));
        Log::debug("The password is ".$request->input('password'));
        if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $token = new \App\AppToken();
            $token->user_id = \Auth::user()->id;
            $token->access_token = str_random(40);
            $token->save();
            Log::debug("Login is successfull".$token);
            echo $token;
        } else {
            Log::debug("Login failed");
            abort('401');
        }
    }

    public function logout(Request $request)
    {
    }
}
