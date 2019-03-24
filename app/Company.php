<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AnthonyMartin\GeoLocation\GeoLocation as GeoLocation;


/**
 * Class Company
 * @package App
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $phone_number
 * @property string $website
 * @property string $email
 * @property integer $template_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Company extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'trade_id',
        'email'
    ];

    protected $dates = [
        'credentialed',
        'iicrc_certified',
        'contractor_warranty',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Returns list of all employees that belong to this company
     *
     */
    public function employees()
    {
        return $this->hasMany('\App\User')->whereIn('role_id', [2,3,4])->orderBy('role_id', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Returns company owner
     */
    public function owner()
    {
        return $this->hasOne('\App\User')->where('role_id', '=', 2)->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function admins()
    {
        return $this->hasMany('\App\User')->whereIn('role_id', [2, 3]);
    }

    /**
     * Attribute for employees count
     */
    public function getEmployeesCountAttribute()
    {
        return $this->employees->count();
    }

    /**
     * @return int
     */
    public function activeEmployeesCount()
    {
        return count($this->employeeLocations());
    }

    /**
     * @return int
     */
    public function getActiveEmployeesCountAttribute()
    {
        return $this->activeEmployeesCount();
    }

    /**
     * Returns list of current Employee locations
     *
     */
    public function employeeLocations()
    {
        $employees = $this->employees;
        $employee_locations = [];

        foreach ($employees as $employee) {
            if (!is_null($employee->location)) {
                $location = [
                    'id' => $employee->id,
                    'unique_id' => $employee->id.'_'.snake_case($employee->name),
                    'company_id' => $this->id,
                    'name' => $employee->name . ' - ' . $this->name . ($employee->trade !== null ? ' - '. $employee->trade->name : ''),
                    'image' => $employee->map_icon,
                    'latitude' => $employee->location->latitude,
                    'longitude' => $employee->location->longitude,
                    'created_at' => $employee->location->created_at->diffForHumans()
                ];
                array_push($employee_locations, $location);
            }
        }

        return $employee_locations;
    }


    /**
     * @return string
     */
    public function getEmployeeLocationsAttribute()
    {
        return json_encode($this->employeeLocations());
    }


    /*
     * Valid code snippet websites
     */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codeSnippetWebsites()
    {
        return $this->hasMany('\App\CodeSnippetWebsite');
    }


    /**
     * Returns all the networks a company is a part of
     */
    public function allNetworks()
    {
        return $this->belongsToMany('\App\Network');
    }

    /**
     * @param $check
     * @return bool
     */
    public function belongsToNetwork($check)
    {
        $networks = $this->allNetworks()->pluck('networks.id')->all();
        if (in_array($check, $networks)) {
            return true;
        }
        return false;
    }

    /**
     * Returns all the networks a company owns
     */
    public function ownedNetworks()
    {
        return $this->hasMany('\App\Network');
    }

    /**
     * Returns pending invitations
     * @return boolean
     */
    public function invitationsPending()
    {
        return $this->hasMany('\App\NetworkInvitation')->where('created_at', '>', \Carbon\Carbon::now()->modify('-3 days'));
    }

    /**
     * @return bool
     */
    public function getHasPendingInvitationsAttribute()
    {
        $count = count($this->invitationsPending);
        if ($count > 0) {
            return true;
        };
        return false;
    }

    /**
     * Returns offices
     */
    public function offices()
    {
        return $this->hasMany('\App\CompanyOffice');
    }

    /**
     * Returns collection of company offices
     */
    public function officeLocations()
    {
        $offices = $this->offices;
        $office_locations = [];

        foreach ($offices as $office) {
            $location = [
                'company_id' => $this->id,
                'office_id' => $office->id,
                'unique_id' => $office->id.'_'.snake_case($office->name),
                'name' => $office->name . ' - ' . $this->name,
                'latitude' => $office->latitude,
                'longitude' => $office->longitude
            ];
            array_push($office_locations, $location);
        }

        return $office_locations;
    }

    /**
     * @return string
     */
    public function getOfficeLocationsAttribute()
    {
        return json_encode($this->officeLocations());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiTokens()
    {
        return $this->hasMany('\App\ApiToken');
    }

    /**
     * @return mixed
     */
    public function activeRequests()
    {
        return $this->belongsToMany('\App\Request')
                    ->where(function($query) {
                        /** going through company_request and getting network requests too **/
                        return $query->where('requests.company_id', '=', $this->id)
                                    ->orWhereNull('requests.company_id');
                    })
                    ->whereNull('closed_on')
                    ->whereNotNull('validated_at')
                    ->orderBy('requests.created_at', 'asc');
    }

    public function closedRequests()
    {
        return $this->hasMany('\App\Request')
                    ->whereNotNull('closed_on');
    }

    /**
     * @return mixed
     */
    public function requestsWithReview()
    {
        return $this->hasMany('\App\Request')
            ->whereNotNull('survey_result')
            ->orderBy('surveyed_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->belongsToMany('\App\Request')
                    ->where(function($query) {
                        return $query->where('requests.company_id', '=', $this->id)
                                    ->orWhereNull('requests.company_id');
                    })
                    ->orderBy('requests.created_at', 'asc');
    }

    /**
     * @return mixed
     */
    public function unclaimedRequests()
    {
        return $this->hasMany('\App\Request')->whereNull('claimed_on');
    }

    /**
     *
     */
    public function hasUserAvailableNear($latitude, $longitude, $radius = 40)
    {
        $center = GeoLocation::fromDegrees($latitude, $longitude);

        foreach ($this->officeLocations() as $location) {
            $office_location = Geolocation::fromDegrees($location['latitude'], $location['longitude']);
            $miles = $center->distanceTo($office_location, 'miles');
            var_dump($miles);
            if ($miles < 40) {
                return true;
            }
        }

        foreach ($this->employeeLocations() as $location) {
            $employee_location = Geolocation::fromDegrees($location['latitude'], $location['longitude']);
            $miles = $center->distanceTo($employee_location, 'miles');
            if ($miles < 40) {
                return true;
            }       
        }

        return false; 
    }

    /**
     * @return string
     */
    public function getAverageRating()
    {
        $ratings = $this->requestsWithReview;
        $number_of_ratings = $ratings->count();
        $sum_of_ratings = $ratings->sum('survey_result');

        if ($number_of_ratings === 0) {
            return 0;
        }
        $average = $sum_of_ratings / $number_of_ratings;

        return $average;

    }

    public function trade()
    {
        return $this->belongsTo('\App\Trade');
    }

    public function codeSnippetWebsite()
    {
        return $this->belongsTo('\App\CodeSnippetWebsite');
    }

    public function photo()
    {
        return $this->belongsTo('\App\Photo');
    }

    public function photos()
    {
        return $this->belongsToMany('\App\Photo');
    }
}
