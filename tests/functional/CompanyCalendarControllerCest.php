<?php
/**
 * Class CompanyReportsControllerCest
 */
class CompanyCalendarControllerCest extends CustomTestCase
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
  

    public function companyCalendar(FunctionalTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->login($user);
        $I->amOnPage('/companies/'.$user->company_id.'/calendar');
        $I->canSee('Requests Calendar'); 
        $request = $this->_newRequest($user->company_id,'Customer Name');
        $I->amOnPage('/companies/'.$user->company_id.'/calendar');

        $I->canSee($user->name); 
    }
}


   
