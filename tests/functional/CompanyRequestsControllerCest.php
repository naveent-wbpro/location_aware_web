<?php

/**
 * Class CompanyRequestsControllerCest
 */
class CompanyRequestsControllerCest extends CustomTestCase
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
        $user = $this->_createUserAndBusiness();
        $I->login($user);
        $I->amOnPage('/companies/'.$user->company_id.'/requests');
        $I->see('Direct Requests');
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryHistory(FunctionalTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->login($user);
        $I->amOnPage('/companies/2/requests/history');
        $I->see('Closed Request');
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryFailedCreate(FunctionalTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->amOnPage('/companies/'.$user->company_id.'/requests/create');
        $I->see('Job Information');
        $I->click('Submit Request');
        $I->see('The customer name field is required.');
        $I->see('The customer email field is required.');
        $I->see('The customer phone number field is required.');
        $I->see('The customer address field is required.');
        $I->see('The customer zipcode field is required.');
    }

    /**
     * @param FunctionalTester $I
     */
    public function trySuccessfulCreate(FunctionalTester $I)
    {
        $customer_name = Faker\Factory::create()->name;
        $user = $this->_createUserAndBusiness();

        $I->amOnPage('/companies/'.$user->company_id.'/requests/create');
        $I->see('Job Information');
        $I->fillField('customer_name', $customer_name);
        $I->fillField('customer_email', 'test@test.com');
        $I->fillField('customer_phone_number', '12345667');
        $I->fillField('customer_address', '2805 Strathmore Drive');
        $I->fillField('customer_zipcode', '30041');
        $I->click('Submit Request');
        $I->see('One Last Step...');

        $request = \App\Request::orderBy('id', 'desc')->first();
        $I->assertEquals($request->customer_name, $customer_name);
        $request->forceDelete();
    }

    public function tryDirectRequest(FunctionalTester $I)
    {
        $customer_name = Faker\Factory::create()->name;
        $user = $this->_createUserAndBusiness();
        $I->login($user);

        $I->amOnPage('/companies/'.$user->company_id.'/requests/create');
        $I->see('Job Information');
        $I->fillField('customer_name', $customer_name);
        $I->fillField('customer_email', 'test@test.com');
        $I->fillField('customer_phone_number', '12345667');
        $I->fillField('customer_address', '2805 Strathmore Drive');
        $I->fillField('customer_zipcode', '30041');
        $I->click('Submit Request');
        $I->see('One Last Step...');
    }

    public function tryNetworkRequest(FunctionalTester $I)
    {
        $customer_name = Faker\Factory::create()->name;
        $user = $this->_createUserAndBusiness();
        $network = $this->newResource(\Mock\Network::create([
            'company_id' => $user->company_id
        ]));

        $I->login($user);
        $I->amOnPage('/companies/'.$user->company_id.'/requests/create?instant_response=1&network_id='.$network->id);
        $I->see('Job Information');
        $I->fillField('customer_name', $customer_name);
        $I->fillField('customer_email', 'test@test.com');
        $I->fillField('customer_phone_number', '12345667');
        $I->fillField('customer_address', '2805 Strathmore Drive');
        $I->fillField('customer_zipcode', '30041');
        $I->click('Submit Request');
        $I->see('One Last Step...');
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryStore(FunctionalTester $I)
    {
        $user = $this->_createUserAndBusiness();
        $I->login($user);
        $I->amOnPage('/companies/'.$user->company_id.'/requests/create');
        $I->see('Job Information');
        $I->fillField('customer_name', 'jeff bezos');    
        $I->fillField('customer_email', 'test@test.com');
        $I->fillField('customer_phone_number', '12345667');
        $I->fillField('customer_address', '2805 Strathmore Drive');
        $I->fillField('customer_zipcode', '30041');
        $I->click('Submit Request');
        $I->see('One Last Step...');
        $I->submitForm('#email-confirmation-form', []);

        $request = \App\Request::orderBy('created_at', 'desc')->first();
        $I->fillField('validation_string', $request->validation_string);
        $I->click('Validate');
        $I->see('Request Received');

        $I->amOnPage('/companies/'.$user->company_id.'/requests');
        $I->see('jeff bezos');
        $I->click('jeff bezos');
        $I->see("$");
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryClaim(FunctionalTester $I)
    {

    }

    /**
     * @param FunctionalTester $I
     */
    public function tryEdit(FunctionalTester $I)
    {

    }

    /**
     * @param FunctionalTester $I
     */
    public function tryUpdateAssignments(FunctionalTester $I)
    {

    }

    /**
     * @param FunctionalTester $I
     */
    public function tryUpdate(FunctionalTester $I)
    {

    }
}
