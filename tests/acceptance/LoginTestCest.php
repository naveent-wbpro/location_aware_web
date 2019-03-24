<?php


/**
 * Class LoginTestCest
 */
class LoginTestCest extends CustomTestCase
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
    public function tryFailedLoginTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->see('Login');
        $I->click('//*[@id="page-top"]/div/div[2]/div/div/div/div[2]/form/div[4]/div/button');
        $I->see('The email field is required.');
        $I->see('The password field is required.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function trySuccessfulLoginTest(AcceptanceTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->amOnPage('/login');
        $I->fillField('email', $user->email);
        $I->fillField('password', 'password');
        $I->click('//*[@id="page-top"]/div/div[2]/div/div/div/div[2]/form/div[4]/div/button');
        $I->see($user->company->name);
    }
}
