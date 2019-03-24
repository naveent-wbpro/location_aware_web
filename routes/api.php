<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    /**
     * Mobile application api routes
     */
    Route::post('/login', 'ApiAuthController@login');
    Route::get('/logout', 'ApiAuthController@logout');
    Route::post('/employee/location', 'CompanyEmployeeLocationsController@store');
    Route::get('/employee/location/history', 'CompanyEmployeeLocationsController@getHistory');
    Route::get('/companies/{id}/offices/locations', 'CompanyOfficesController@getLocations');
    Route::get('/companies/{id}/employees/locations', 'CompanyEmployeeLocationsController@index');
    Route::get('/code_snippets', 'ApiCodeSnippetsController@index');

    /**
     * API routes
     */
    Route::group(['middleware' => 'apitoken', 'namespace' => 'Api'], function () {
        Route::get('/companies/{id}', 'ApiCompaniesController@show');
        Route::get('/companies/{id}/networks', 'ApiCompanyNetworksController@index');
        Route::get('/companies/{id}/requests', 'ApiCompanyRequestsController@index');
        Route::post('/companies/{id}/requests', 'ApiCompanyRequestsController@store');
        Route::get('/companies/{id}/offices', 'ApiCompanyOfficesController@index');
        Route::get('/companies/{id}/employees', 'ApiCompanyEmployeesController@index');
        Route::post('/companies/{id}/employees', 'ApiCompanyEmployeesController@store');
        Route::get('/companies/monitoring/{id}', 'ApiCompaniesController@monitoring');
    });
