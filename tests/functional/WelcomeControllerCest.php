<?php


/**
 * Class WelcomeCest
 */
class WelcomeControllerCest
{
    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryIndex(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->see('Providing EVERY Company in ANY Industry On-Demand Service Technology');
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryContactUs(FunctionalTester $I)
    {
        $I->amOnPage('/');

        $I->fillField('name', 'test');
        $I->fillField('email', 'success@simulator.amazonses.com');
        $I->fillField('phone', 'message');
        $I->fillField('message', 'message');
        $I->click('Send Message');
        $I->seeResponseCodeIs(200);
    }
}
