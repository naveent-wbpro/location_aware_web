<?php

namespace App;

use AnthonyMartin\GeoLocation\GeoLocation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Billable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'email_verification_token',
        'company_id',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'card_last_four', 'card_brand', 'stripe_id', 'trial_ends_at'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'updated_at',
        'created_at',
        'employee_since'
    ];

    /**
     * @param string $string
     *
     * @return string
     */
    public function setPhoneAttribute($string)
    {
        $number = preg_replace("/[^0-9]/", "", $string);
        $this->attributes['phone'] = $number;

        return $number;
    }

    public function getRawPhoneAttribute($string)
    {
        return preg_replace("/[^0-9]/", "", $this->attributes['phone']);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function getPhoneAttribute($string)
    {
        $str_length = strlen($string);
        if ($str_length == 10) {
            return '('.substr($string, 0, 3).') '.substr($string, 3, 3).'-'.substr($string, 6, 4);
        }
        if ($str_length == 11) {
            return '+'.substr($string, 0, 1).' ('.substr($string, 1, 3).') '.substr($string, 4, 3).'-'.substr($string, 7, 4);
        }
        if ($str_length == 12) {
            return '+'.substr($string, 0, 2).' ('.substr($string, 2, 3).') '.substr($string, 5, 3).'-'.substr($string, 8, 4);
        }

        if (!empty($string)) {
            return $string;
        }

        return;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Returns company relation
     *
     */
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    /**
     * Returns office relation
     *
     */
    public function office()
    {
        return $this->belongsTo('\App\CompanyOffice', 'company_office_id');
    }

    /**
     * Returns the latest location for a user if updated in the last 15 minutes
     *
     * @return array of locations
     */
    public function location()
    {
        return $this->hasOne('App\Location')->where('created_at', '>', \Carbon\Carbon::now()->modify('15 minutes ago'))->orderBy('created_at', 'desc');
    }


    /**
     * Returns all the requests a user is attached to
     */
    public function requests()
    {
        return $this->belongsToMany('\App\Request');
    }

    /**
     * Returns number of employees allowed
     *
     * @return integer
     */
    public function numberOfEmployees()
    {
        $employees = 5;
        $employees_paid = 0;

        if ($this->subscription('main')) {
            $employees_paid = ($this->subscription('main')->quantity - 45) / 5;
        }

        return $employees + $employees_paid;
    }

    /**
     *
     */
    public function getNumberOfEmployeesAllowedAttribute()
    {
        return $this->numberOfEmployees();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profilePhoto()
    {
        return $this->belongsTo('\App\Photo', 'profile_photo_id');
    }

    /**
     */
    public function stripeEvents()
    {
        return $this->hasMany('\App\StripeEvent', 'customer_id', 'stripe_id');
    }

    /**
     * @param $start
     * @param $end
     * @return string
     */
    public function totalActiveGpsTime($start, $end)
    {
        $locations = \App\Location::where('user_id', '=', $this->id)->whereBetween('created_at', [$start, $end])->select('created_at')->get();
        $seconds = 0;
        foreach ($locations as $key => $location) {
            if (isset($locations[$key+1])) {
                if ($location->created_at->diffInSeconds($locations[$key+1]->created_at) < 900) {
                    $seconds = $seconds + $location->created_at->diffInSeconds($locations[$key+1]->created_at);
                }
            }
        }

        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        $string = '';

        if ($hours != 0) {
            $string .= $hours.' hours and ';
        }
        if ($minutes != 0) {
            $string .= $minutes .' minutes';
        }

        return $string;
    }

    /**
     * @param $start
     * @param $end
     * @return string
     */
    public function totalActiveGpsTimeRequest($start, $end, $user_id)
    {
        $locations = \App\Location::where('user_id', '=', $user_id)->whereBetween('created_at', [$start, $end])->select('created_at')->get();
        $seconds = 0;
        foreach ($locations as $key => $location) {
            if (isset($locations[$key+1])) {
                if ($location->created_at->diffInSeconds($locations[$key+1]->created_at) < 900) {
                    $seconds = $seconds + $location->created_at->diffInSeconds($locations[$key+1]->created_at);
                }
            }
        }

        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        $string = '';

        if ($hours != 0) {
            $string .= $hours.' hours and ';
        }
        if ($minutes != 0) {
            $string .= $minutes .' minutes';
        }

        return $string;
    }

    /**
     * @param $start_date
     * @param $end_date
     * @return int
     */
    public function totalDistanceTravelled($start_date, $end_date)
    {
        $locations = \App\Location::where('user_id', '=', $this->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->select(['latitude', 'longitude', 'created_at'])
            ->get();

        $final_distance = 0;

        foreach ($locations as $key => $location) {
            if (isset($locations[$key+1])) {
                $current_location = GeoLocation::fromDegrees($location->latitude, $location->longitude);
                $new_location = GeoLocation::fromDegrees($locations[$key+1]->latitude, $locations[$key+1]->longitude);

                $distance = $current_location->distanceTo($new_location, 'miles');

                if (!is_nan($distance)) {
                    $final_distance = $final_distance + $distance;
                }
            }
        }

        return round($final_distance, 2);
    }

     /**
     * @param $start_date
     * @param $end_date
     * @param $user_id
     * @return int
     */

    public function totalDistanceTravelledByrequest($start_date, $end_date, $user_id)
    {
        $locations = \App\Location::where('user_id', '=', $user_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->select(['latitude', 'longitude', 'created_at'])
            ->get();

        $final_distance = 0;

        foreach ($locations as $key => $location) {
            if (isset($locations[$key+1])) {
                $current_location = GeoLocation::fromDegrees($location->latitude, $location->longitude);
                $new_location = GeoLocation::fromDegrees($locations[$key+1]->latitude, $locations[$key+1]->longitude);

                $distance = $current_location->distanceTo($new_location, 'miles');

                if (!is_nan($distance)) {
                    $final_distance = $final_distance + $distance;
                }
            }
        }

        return round($final_distance, 2);
    }

    /**
     * @param $start_date
     * @param $end_date
     * @return array
     */
    public function tripsBetween($start_date, $end_date)
    {
        $locations = \App\Location::where('user_id', '=', $this->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->select(['latitude', 'longitude', 'created_at'])
            ->get();

        $trips = [];

        if (count($locations) > 0) {
            $single_trip = [
                'distance_travelled' => 0,
                'time_in_seconds' => 0,
                'start_time' => $locations->first()->created_at
            ];
        }

        foreach ($locations as $key => $location) {
            if (isset($locations[$key+1])) {
                $location_one = clone $location;
                $location_two = clone $locations[$key+1];
                if ($location_two->created_at->diffInMinutes($location_one->created_at) < 15) {
                    $current_location = GeoLocation::fromDegrees($location->latitude, $location->longitude);
                    $new_location = GeoLocation::fromDegrees($locations[$key+1]->latitude, $locations[$key+1]->longitude);
                    if (!is_nan($current_location->distanceTo($new_location, 'miles'))) {
                        $single_trip['distance_travelled'] += $current_location->distanceTo($new_location, 'miles');
                        $single_trip['time_in_seconds'] += $location->created_at->diffInSeconds($locations[$key+1]->created_at);
                        if (!isset($single_trip['start_time'])) {
                            $single_trip['start_time'] = $location_one->created_at;
                        }
                    }
                } else {
                    $single_trip['end_time'] = $location_one->created_at;
                    array_push($trips, $single_trip);
                    $single_trip = [
                        'distance_travelled' => 0,
                        'time_in_seconds' => 0
                    ];
                }
            } else {
                $single_trip['end_time'] = $location_one->created_at;
                array_push($trips, $single_trip);
            }
        }

        return array_reverse($trips);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignments()
    {
        return $this->belongsToMany('\App\Request');
    }

    /**
     * @return mixed
     */
    public function activeAssignments()
    {
        return $this->belongsToMany('\App\Request')->whereNull('requests.closed_on');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignmentsBetween($start_date, $end_date)
    {
        return $this->belongsToMany('\App\Request')->whereBetween('requests.created_at', [$start_date, $end_date]);
    }

    /**
     * @return string
     */
    public function getTimezoneAttribute()
    {
        if ($this->attributes['timezone'] == null) {
            return 'UTC';
        }
        return $this->attributes['timezone'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trade()
    {
        return $this->belongsTo('\App\Trade');
    }

    /**
     * @return string
     */
    public function getMapIconAttribute()
    {
        switch ($this->trade_id) {
            case 1:
                //Engineers
                return 'black-car.png';
                break;
            case 2:
                //independent adjusters
                return 'automobile.png';
                break;
            case 3:
                //insurance company adjusters
                return 'brown-truck.png';
                break;
            case 4:
                //mitigation contractors
                return 'transport.png';
                break;
            case 5:
                //roofing contractors
                return 'yellow-truck.png';
                break;
            case 6:
                //general contractors
                return 'truck.png';
            default:
                return 'suv.png';
        }
    }
}
