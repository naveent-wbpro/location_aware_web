<?php

use \Codeception\Util\HttpCode;

/**
 * Class CompanyEmployeesCest
 */
class CompanyEmployeesCest extends CustomTestCase
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

    // tests
    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateEmployee(AcceptanceTester $I)
    {
        $employee_name = str_shuffle('johnsmith');
        $data = [
            'name' => $employee_name,
            'email' => 'success+'.str_shuffle('asdfasdfadf').'@simulator.amazonses.com'
        ];
        $user = $this->_createUserAndBusiness();
        $api_token = \Mock\ApiToken::create(['company_id' => $user->company_id]);

        $I->sendPost('api/companies/'.$user->company_id.'/employees?access_token='.$api_token->access_token, $data);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->login($user);
        $I->click('Employees');
        $I->see('Invite New Employee');
        $I->see($employee_name);
    }
}
