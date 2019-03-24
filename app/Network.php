<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{

    /**
     * returns the owner of the network
     */
    public function owner()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    /**
     * returns all companies that belong to this network
     */
    public function companies()
    {
        return $this->belongsToMany('App\Company');
    }

    public function companyOffices()
    {
        $companies = [];
        foreach ($this->companies as $company) {
            if (!empty(json_decode($company->officeLocations))) {
                $company_office_locations = json_decode($company->officeLocations);
                foreach ($company_office_locations as $location) {
                    array_push($companies, $location);
                }
            }
        }
        return json_encode($companies);
    }

    public function getOfficeLocationsAttribute()
    {
        return $this->companyOffices();
    }

    /**
     * Returns all employees assocciated with a network
     */
    public function employeeLocations()
    {
        $employees = [];
        foreach ($this->companies as $company) {
            if (!empty(json_decode($company->employeeLocations))) {
                $company_employee_locations = json_decode($company->employeeLocations);
                foreach ($company_employee_locations as $location) {
                    array_push($employees, $location);
                }
            }
        }
        return json_encode($employees);
    }

    public function getEmployeeLocationsAttribute()
    {
        return $this->employeeLocations();
    }


    /**
     * returns the number of companies in this network
     */
    public function countCompanies()
    {
        return $this->companies->count();
    }

    /**
     * Attribute for counting of companies
     */
    public function getCompaniesCountAttribute()
    {
        return $this->countCompanies();
    }

    public function getEmployeesCountAttribute()
    {
        $companies = $this->companies;
        $count = 0;
        foreach ($companies as $company) {
            $count += $company->employeesCount;
        }
        return $count;
    }

    public function getActiveEmployeesCountAttribute()
    {
        $companies = $this->companies;
        $count = 0;
        foreach ($companies as $company) {
            $count += $company->activeEmployeesCount;
        }
        return $count;
    }

    public function networkRequestsThisMonth()
    {
        return $this->hasMany('\App\Request')->where('created_at', '>', \Carbon\Carbon::now()->subMonth());
    }
}
