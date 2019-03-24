<?php


/**
 * Class RequestsTestCest
 */
class RequestsTestCest extends CustomTestCase
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
    }
    /**
     * @param AcceptanceTester $I
     */
    public function tryToSeeRequests(AcceptanceTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->login($user);
        $I->amOnPage('/companies/'.$user->company_id.'/requests');
        $I->see('DIRECT REQUESTS');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateRequestViaApi(AcceptanceTester $I)
    {
        $customer_name = str_random();
        $data = [
            'customer_name' => $customer_name,
            'customer_email' => str_shuffle('adfasdf32').'@test.com',
            'customer_phone_number' => str_shuffle('11231223123'),
            'customer_address' => '2805 Strathmore Drive Cumming GA',
            'customer_zipcode' => 30041
        ];

        $user = $this->_createUserAndBusiness();
        $api_token = \Mock\ApiToken::create(['company_id' => $user->company_id]);

        $I->sendPost('/api/companies/'.$user->company_id.'/requests?access_token='.$api_token->access_token, $data);

        $I->login($user);
        $I->see($customer_name);
        $I->click($customer_name);
        $I->see('Customer Name');
    }
}
