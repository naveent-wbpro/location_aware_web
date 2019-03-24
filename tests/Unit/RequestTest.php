<?php


class RequestTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     *
     */
    public function testArrivedAtAndDepartedAtLocation()
    {
        $company = \Mock\Company::create();
        $user = \Mock\User::create([
            'company_id' => $company->id,
            'role_id' => 3
        ]);
        $request = \Mock\Request::create([
            'company_id' => $company->id,
            'created_at' => \Carbon\Carbon::now()->subMonth(),
            'customer_address' => '2806 strathmore drive cumming ga'
        ]);
        $request->employees()->attach($user->id);

        \Mock\Location::create($user->id);

        $check_request = \App\Request::find($request->id);
        $this->assertNull($check_request->arrived_on);
        $this->assertNull($check_request->arrived_on_user_id);

        \Mock\Location::create($user->id, [
            'latitude' => $check_request->latitude,
            'longitude' => $check_request->longitude,
        ]);

        $check_request = \App\Request::find($request->id);

        $this->assertEquals($user->id, $check_request->arrived_on_user_id);
        $this->assertNotNull($check_request->arrived_on);

        \Mock\Location::create($user->id, [
            'latitude' => 33.8864272,
            'longitude' => -84.211249
        ]);

        $check_request = \App\Request::find($request->id);

        $this->assertNotNull($check_request->departed_on);
    }
}
