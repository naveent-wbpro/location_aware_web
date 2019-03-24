<?php

class CompanyEmployeeLocationsControllerCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function tryStore(ApiTester $I)
    {
        $user = \Mock\User::create();
        $access_token = \Mock\AppToken::create($user->id);

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('access_token', $access_token->access_token);
        $I->haveHttpHeader('user_id', $user->id);

        $I->sendPOST('/api/employee/location', [
            'latitude' => '32.3405131',
            'longitude' => '-90.0825253'
        ]);

        $I->seeResponseContainsJson([
            'user_id' => $user->id,
            'latitude' => '32.3405131',
            'longitude' => '-90.0825253'
        ]);
    }

    /**
     * @param ApiTester $I
     *
     */
    public function tryIndex(ApiTester $I)
    {
        $user = \Mock\User::create();
        \Mock\Location::create($user->id);

        $code_snippet = \Mock\CodeSnippetWebsite::create();

        $I->sendGET('/api/companies/'.$code_snippet->company_id.'/employees/locations?api_key='.$code_snippet->api_key);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'unique_id' => 'string',
            'company_id' => 'integer',
            'name' => 'string',
            'latitude' => 'string',
            'longitude' => 'string',
            'created_at' => 'string'
        ]);
    }
}
