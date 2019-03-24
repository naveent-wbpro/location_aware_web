<?php

/**
 * Created by PhpStorm.
 * User: albertleao
 * Date: 3/14/17
 * Time: 1:33 PM
 */
class CustomTestCase
{
    /**
     * @var array
     */
    protected $new_resources = [];

    /**
     * @param mixed $I
     */
    public function _before($I)
    {
    }

    /**
     * @param mixed $I
     */
    public function _after($I)
    {
        foreach ($this->new_resources as $resource) {
            $resource->forceDelete();
        }
    }

    /**
     * @param ApiTester $I
     */
    public function _failed(ApiTester $I)
    {
        foreach ($this->new_resources as $resource) {
            $resource->forceDelete();
        }
    }

    /**
     * @param mixed $input
     * @return mixed
     */
    protected function newResource($input)
    {
        array_push($this->new_resources, $input);

        return $input;
    }


    /**
     * @return mixed
     */
    public function _createUserAndBusiness()
    {
        $company = $this->newResource(\Mock\Company::create());
        $user = $this->newResource(\Mock\User::create(['company_id' => $company->id, 'role_id' => 2]));

        return $user;
    }

    public function _newRequest($company_id,$customer_name)
    {
         $request = $this->newResource(\Mock\Request::create([
            'company_id' => $company_id,
            'created_at' => \Carbon\Carbon::now()->subMonth(),
            'customer_name' => $customer_name,
            'customer_address' => '2806 strathmore drive cumming ga'
        ]));

        return $request;
    }



}
