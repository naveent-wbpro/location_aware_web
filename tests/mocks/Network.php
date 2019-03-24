<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 4/6/17
 * Time: 2:58 AM
 */

namespace Mock;


class Network
{
    /**
     * @param array $data
     * @return \App\Network
     */
    public static function create($data = [])
    {
        $faker = \Faker\Factory::create();
        $network = new \App\Network();
        $network->company_id = $data['company_id'];
        $network->name = $faker->company;
        $network->description = $faker->realText;
        $network->save();

        $company = \App\Company::find($data['company_id']);
        $company->allNetworks()->attach($network->id);

        return $network;
    }
}
