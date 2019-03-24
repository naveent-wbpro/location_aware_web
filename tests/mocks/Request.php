<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 4/7/17
 * Time: 1:48 PM
 */

namespace Mock;


class Request
{
    /**
     * @param array $data
     * @return \App\Request
     */
    public static function create($data = [])
    {
        $faker = \Faker\Factory::create();
        $request = new \App\Request();
        $request->company_id = $data['company_id'];
        $request->customer_name = isset($data['customer_name']) ? $data['customer_name'] : $faker->name;
        $request->customer_email = $faker->email;
        $request->customer_address = isset($data['customer_address']) ? $data['customer_address'] : $faker->address;
        $request->customer_phone_number = $faker->phoneNumber;
        $request->created_at = isset($data['created_at']) ? $data['created_at'] : \Carbon\Carbon::now();
        $request->save();

        return $request;
    }
}
