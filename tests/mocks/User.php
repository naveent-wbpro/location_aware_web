<?php

namespace Mock;
use \Illuminate\Support\Facades\Hash;

/**
 * Class User
 * @package Mock
 */
class User
{
    /**
     * @param array $data
     */
    public static function create($data = [])
    {
        $user = new \App\User();
        $user->name = str_random() . ' ' . str_random();
        $user->email = str_random().'@email.com';
        $user->password = Hash::make('password');
        $user->company_id = isset($data['company_id']) ? $data['company_id'] : 2;
        $user->role_id = isset($data['role_id']) ? $data['role_id'] : 2;
        $user->email_verified = 1;
        $user->save();

        return $user;
    }
}
