<?php
/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 3/14/17
 * Time: 1:14 PM
 */

namespace Mock;


class Company
{
    /**
     * @param array $data
     * @return \App\Company
     */
    public static function create($data = [])
    {
        /** @var \App\Company $company */
        $company = new \App\Company();
        $company->name = isset($data['name']) ? $data['name'] : str_random();
        $company->email = isset($data['email']) ? $data['email'] : 'success@simulator.amazonses.com';
        $company->save();

        return $company;
    }
}