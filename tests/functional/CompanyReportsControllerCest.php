<?php
/**
 * Class CompanyReportsControllerCest
 */
class CompanyReportsControllerCest extends CustomTestCase
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
  

    public function companyReport(FunctionalTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->login($user);
        $I->amOnPage('/companies/'.$user->company_id.'/reports/'.$user->id.'');
       

        $I->fillField('from_date', \Carbon\Carbon::now()->subWeek());
        $I->fillField('to_date', \Carbon\Carbon::now());
        $I->click('button[type=submit]');
        $I->see('Total');
       

        $I->fillField('from_date', new \Carbon\Carbon('first day of previous month'));
        $I->fillField('to_date', new \Carbon\Carbon('last day of previous month'));
        $I->click('button[type=submit]');
        $I->see('Total');

        $I->fillField('from_date',"-3 months", strtotime(date("m/d/Y", strtotime(\Carbon\Carbon::now()->firstOfQuarter()))));
        $I->fillField('to_date',"-3 months", strtotime(date("m/d/Y", strtotime(\Carbon\Carbon::now()->lastOfQuarter()))));
        $I->click('button[type=submit]');
        $I->see('Total');


        $I->fillField('from_date',"last sunday midnight",strtotime("-1 week +1 day"));
        $I->fillField('to_date',"next saturday",strtotime("last sunday midnight",strtotime("-1 week +1 day")));
        $I->click('button[type=submit]');
        $I->see('Total');


        $I->fillField('from_date', new \Carbon\Carbon('first day of this month'));
        $I->fillField('to_date', \Carbon\Carbon::now());
        $I->click('button[type=submit]');
        $I->see('Total');

        $I->fillField('from_date', new \Carbon\Carbon('first day of January last year'));
        $I->fillField('to_date', new \Carbon\Carbon('last day of December last year'));
        $I->click('button[type=submit]');
        $I->see('Total');

    }
  
}


   