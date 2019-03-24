<?php

/**
 * Class UserTest
 */
class UserTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     *
     */
    protected function _before()
    {
    }

    /**
     *
     */
    protected function _after()
    {
    }

    /**
     *
     */
    public function testDistanceTravelled()
    {
        $user = \Mock\User::create();
        $location_one = \Mock\Location::create($user->id, [
            'latitude' => 38.898556,
            'longitude' => -77.037852
        ]);

        $location_two = \Mock\Location::create($user->id, [
            'latitude' => 38.898556,
            'longitude' => -77.0564300
        ]);

        $distance_travelled = $user->totalDistanceTravelled(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now());

        $this->assertEquals('1', $distance_travelled);
    }

    /**
     *
     */
    public function testAssignments()
    {
        $company = \Mock\Company::create();
        $user = \Mock\User::create([
            'company_id' => $company->id,
            'role_id' => 3
        ]);
        $request = \Mock\Request::create([
            'company_id' => $company->id
        ]);

        $request->employees()->attach($user->id);

        $this->assertEquals(1, $user->assignments->count());
        $this->assertEquals($request->customer_name, $user->assignments->first()->customer_name);
    }

    /**
     *
     */
    public function testTripsBetweenDates()
    {
        $company = \Mock\Company::create();
        $user = \Mock\User::create([
            'company_id' => $company->id,
            'role_id' => 3
        ]);

        for ($i = 0; $i < 10; $i++) {
            if($i % 2 == 0) {
                \Mock\Location::create($user->id, [
                    'latitude' => 38.898556,
                    'longitude' => -77.037852,
                    'created_at' => \Carbon\Carbon::now()->subMinutes($i)
                ]);
            } else {
                \Mock\Location::create($user->id, [
                    'latitude' => 38.898556,
                    'longitude' => -77.0564300,
                    'created_at' => \Carbon\Carbon::now()->subMinutes($i)
                ]);
            }
        }

        for ($i = 100; $i < 110; $i++) {
            if($i % 2 == 0) {
                \Mock\Location::create($user->id, [
                    'latitude' => 38.898556,
                    'longitude' => -77.037852,
                    'created_at' => \Carbon\Carbon::now()->subMinutes($i)
                ]);
            } else {
                \Mock\Location::create($user->id, [
                    'latitude' => 38.898556,
                    'longitude' => -77.0564300,
                    'created_at' => \Carbon\Carbon::now()->subMinutes($i)
                ]);
            }
        }

        $this->assertEquals(2, count($user->tripsBetween(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now())));
    }

    /**
     *
     */
    public function testAssignmentsBetweenDates()
    {
        $company = \Mock\Company::create();
        $user = \Mock\User::create([
            'company_id' => $company->id,
            'role_id' => 3
        ]);
        $request = \Mock\Request::create([
            'company_id' => $company->id,
            'created_at' => \Carbon\Carbon::now()->subMonth()
        ]);
        $request->employees()->attach($user->id);

        $first_request = \Mock\Request::create([
            'company_id' => $company->id
        ]);
        $first_request->employees()->attach($user->id);

        $second_request = \Mock\Request::create([
            'company_id' => $company->id
        ]);
        $second_request->employees()->attach($user->id);

        $this->assertEquals(2, $user->assignmentsBetween(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now())->count());
        $this->assertEquals($first_request->customer_name, $user->assignmentsBetween(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now())->first()->customer_name);
    }
}
