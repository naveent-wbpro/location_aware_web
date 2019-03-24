<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AnthonyMartin\GeoLocation\GeoLocation as GeoLocation;

/**
 * Class Request
 * @package App
 */
class Request extends Model
{

    protected $attributes = [
        'description' => ''
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone_number',
        'customer_phone_number_type',
        'customer_address',
        'customer_zipcode',
        'description',
        'employee_id',
        'instant_response',
        'network_id',
        'office_id',
        'scheduled_date',
        'scheduled_time_window_id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'scheduled_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'contacted_on',
        'assigned_on',
        'arrived_on',
        'surveyed_at',
        'departed_on',
        'closed_on',
        'claimed_on',
        'survey_sent_at',
        'validated_at',
    ];

    /**
     *
     */
    public static function boot()
    {
        Request::saving(function ($model) {
            /** @var \App\Request $model */
            $model->updateLocation();
        });

        Request::updated(function ($model) {
            if ($model->closed_on !== null && $model->survey_sent_at === null) {
                $model->survey_sent_at = \Carbon\Carbon::now();
                $model->save();

                $cm = new Libraries\CustomerThermometer();
                $cm->sendEmail($model);
            }
        });
    }

    public function getAddressAttribute()
    {
        return $this->customer_address . ' ' . $this->customer_city . ' ' . $this->customer_zipcode . ' ' . $this->customer_state;
    }

    /**
     * @return string
     */
    public function getCustomerPhoneNumberTypeAttribute($value)
    {
        if ($value == 1) {
            return 'Cell';
        } elseif ($value == 2) {
            return 'Home';
        }
    }


    /**
     * @param string $string
     *
     * @return string
     */
    public function setCustomerPhoneNumberAttribute($string)
    {
        $number = preg_replace("/[^0-9]/", "", $string);
        $this->attributes['customer_phone_number'] = $number;

        return $number;
    }

    public function getRawCustomerPhoneNumberAttribute($string)
    {
        return preg_replace("/[^0-9]/", "", $this->attributes['customer_phone_number']);
    }

    /**
     *
     */
    public function updateLocation()
    {
        if ($this->latitude == null || $this->longitude == null) {
            $coords = \App\Libraries\Coordinates::streetAddress($this->customer_address.' '.$this->customer_city.' '.$this->customer_state.' '.$this->customer_zipcode);
            $this->latitude = @$coords->latitude;
            $this->longitude = @$coords->longitude;
        }
    }


    /**
     * Returns a company in which this request belongs to.
     */
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('\App\User', 'employee_id');
    }

    public function arrivalUser()
    {
        return $this->belongsTo('\App\User', 'arrived_on_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function office()
    {
        return $this->belongsTo('\App\CompanyOffice', 'office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany('\App\User')->withPivot('acknowledged_at', 'on_the_way_at');
    }

    /**
     *
     */
    public function getEmployeesAcknowledgedAttribute()
    {
        return $this->employees->filter(function($item) {
            return $item->pivot->acknowledged_at;
        });
    }

    /**
     *
     */
    public function getEmployeesOnTheWayAttribute()
    {
        return $this->employees->filter(function($item) {
            return $item->pivot->on_the_way_at;
        });
    }

    /**
     * @return array
     */
    public function employeeLocations()
    {
        $employees = $this->employees;
        $employee_locations = [];

        foreach ($employees as $employee) {
            if (!is_null($employee->location)) {
                $location = [    'id' => $employee->id,
                    'unique_id' => $employee->id.'_'.snake_case($employee->name),
                    'company_id' => $this->id,
                    'name' => $employee->name,
                    'latitude' => $employee->location->latitude,
                    'longitude' => $employee->location->longitude,
                    'created_at' => $employee->location->created_at->diffForHumans()];
                array_push($employee_locations, $location);
            }
        }

        return $employee_locations;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return float | null
     */
    public function distanceFrom($latitude, $longitude)
    {
        if (!empty($this->latitude) && !empty($this->longitude)) {
            $request_location = GeoLocation::fromDegrees($this->latitude, $this->longitude);
            $employee_location = GeoLocation::fromDegrees($latitude, $longitude);

            return number_format($request_location->distanceTo($employee_location, 'miles'));
        }
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function network()
    {
        return $this->belongsTo('\App\Network');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeWindow()
    {
        return $this->belongsTo('\App\TimeWindow', 'scheduled_time_window_id');
    }

    public function companyGeneratedUser()
    {
        return $this->belongsTo('\App\User', 'company_generated_user_id');
    }

    /**
     * @return string
     */
    public function getClickedOnAttribute()
    {
        if ($this->employee_id != null) {
            return !empty($this->employee) ? $this->employee->name : '' ;
        }
        if ($this->office_id != null) {
            return !empty($this->office) ? $this->office->name : '';
        }
        if ($this->company_generated_user_id != null) {
            return 'Company Generated';
        }
        return 'Instant Response';
    }

    public function customFields()
    {
        return $this->hasMany('\App\CustomResponse');
    }

    /**
     *
     */
    public function getScheduledDateTime($timezone)
    {
        if (!empty($this->scheduled_time_window_id)) {
            $datetime = new \Carbon\Carbon($this->scheduled_date, $timezone);
            if ($this->scheduled_time_window_id === 1) {
                $datetime->hour(8);
            } 
            if ($this->scheduled_time_window_id === 2) {
                $datetime->hour(10);
            }
            if ($this->scheduled_time_window_id === 3) {
                $datetime->hour(12);
            }
            if ($this->scheduled_time_window_id === 4) {
                $datetime->hour(14);
            }
            if ($this->scheduled_time_window_id === 5) {
                $datetime->hour(16);
            }
            return $datetime;
        }
        return $this->created_at;
    }
}
