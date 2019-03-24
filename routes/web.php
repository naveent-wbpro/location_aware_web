<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

  /** Welcome Page Routes **/
  Route::get('/', 'WelcomeController@index');
  Route::post('/', 'WelcomeController@contactUs');
  Route::get('/privacy_policy', 'LegalDocumentsController@privacyPolicy');
  Route::get('/terms_of_use', 'LegalDocumentsController@termsOfUse');
  #Route::get('/playground', 'PlaygroundController@index');
  Route::get('support', 'SupportController@index');
  Route::get('/docs/api', function () {
      return view('docs.api');
  });

  Route::get('companies', 'CompaniesController@index');
  Route::get('companies/compare', 'CompaniesController@compare');
  Route::get('companies/{id}', 'CompaniesController@show');
  Route::resource('companies/{id}/photos', 'CompanyPhotosController');

  Route::get('email_verification/update', 'EmailVerificationController@update');
  Route::resource('email_verification', 'EmailVerificationController');

  Route::resource('maps', 'MapsController');

  /** Stripe webhook **/
  Route::post('stripe/webhook', 'WebhookController@handleWebhook');

  /** Auth Routes **/
  Route::auth();

  Route::group(['middleware' => ['auth']], function () {

    /** Account Settings Routes **/
    Route::get('account_settings', 'AccountSettingsController@index');
    Route::put('account_settings', 'AccountSettingsController@update');
    Route::get('billing', 'BillingController@index');
    Route::get('billing/edit', 'BillingController@edit');
    Route::put('billing', 'BillingController@update');
    Route::post('billing/destroy', 'BillingController@destroy');
    Route::get('plans', 'PlansController@index');
    Route::put('plans', 'PlansController@update');
  });

  /** Only administrators can see this **/
  Route::group(['middleware' => ['auth', 'auth.admin'], 'as' => 'admin.'], function () {
    Route::get('admin', 'AdminController@index');
    Route::resource('admin/users', 'AdminUsersController');
    Route::get('admin/users/{id}/reset_password', 'AdminUsersController@reset_password');
    Route::resource('admin/requests', 'AdminRequestsController');
    Route::resource('admin/billing', 'AdminBillingController');
    Route::resource('admin/companies', 'AdminCompaniesController');
    Route::resource('admin/networks', 'AdminNetworksController');
    Route::resource('admin/ads', 'AdminAdsController');
  });


  /** Only authenticated companies can see this **/
  Route::group(['middleware' => ['auth.company'], 'prefix' => 'companies/{company_id}'], function () {
    Route::get('edit', 'CompaniesController@edit');
    Route::post('update', ['uses' => 'CompaniesController@update', 'as' => 'companies.{company_id}.update']);

    Route::resource('offices', 'CompanyOfficesController');
    Route::resource('employees', 'CompanyEmployeesController');

    Route::get('maps', 'CompanyMapsController@index');
    Route::get('maps/employee_locations', 'CompanyMapsController@employee_locations');
    Route::get('maps/office_locations', 'CompanyMapsController@office_locations');

    Route::get('updates', 'CompanyUpdatesController@index');

    Route::post('networks/{id}/search', ['uses' => 'CompanyNetworkCompaniesController@search', 'as' => 'companies.{company_id}.networks.{id}.search']);

    Route::delete('networks/{id}/leave', ['uses' => 'CompanyNetworkCompaniesController@leave', 'as' => 'companies.{company_id}.networks.{network_id}.companies.leave']);
    Route::resource('networks/{network_id}/companies', 'CompanyNetworkCompaniesController');
    ;
    Route::resource('networks', 'CompanyNetworksController');
    Route::get('networks/{network_id}/map', 'CompanyNetworksController@map');
    Route::get('networks/{network_id}/locations', 'CompanyNetworksController@locations');

    Route::resource('code_snippet', 'CompanyCodeSnippetController');
    
    Route::get('requests/{requests}/assignments',
        ['uses' => 'CompanyRequestsController@assignments']);
    Route::put('requests/{requests}/assignments',
        ['uses' => 'CompanyRequestsController@updateAssignments', 'as' => 'companies.{company_id}.requests.{request_id}/assignments']);
    Route::get('requests/{request_id}/edit', 'CompanyRequestsController@edit');
    Route::post('requests/{request_id}/claim',
        ['uses' => 'CompanyRequestsController@claim', 'as' => 'companies.{company_id}.requests.claim']);
    Route::resource('requests/history', 'CompanyHistoricalRequestsController');
    Route::get('requests/{id}/resend_survey', 'CompanyHistoricalRequestsController@resendSurvey');
    Route::resource('requests', 'CompanyRequestsController');
    Route::resource('api_tokens', 'CompanyApiTokensController');
    Route::resource('reports', 'CompanyReportsController');
    Route::resource('forms', 'CompanyFormsController');
    Route::post('forms/update_code_snippets',[
     'uses' => 'CompanyFormsController@updateCodeSnippets',
     'as'   => 'companies.{company_id}.forms.update_code_snippets'
    ]);
    Route::post('reports/{user_id}', 'CompanyReportsController@show');
    Route::get('csv/{user_id}', 'CompanyReportsController@csv');
    Route::post('/ajax_request_update', 'CompanyReportsController@update_request_type');
  
    Route::get('calendar/download', 'CompanyCalendarController@download');
    Route::resource('calendar', 'CompanyCalendarController');
    Route::post('calendar/', 'CompanyCalendarController@index');
    Route::resource('monitoring', 'CompanyMonitoringController');
  });

  Route::get('/companies/{company_id}/requests/create', 'CompanyRequestsController@create');
    
  Route::get('/companies/{company_id}/requests/{request_id}/acknowledge', 'CompanyRequestsController@acknowledge');
  Route::get('/companies/{company_id}/requests/{request_id}/on_the_way', 'CompanyRequestsController@onTheWay');
  Route::get('/companies/{company_id}/requests/{request_id}/validation_choices', 'CompanyRequestsController@validationChoices');
  Route::post('/companies/{company_id}/requests/store', ['uses' => 'CompanyRequestsController@store', 'as' => 'companies.{company_id}.requests.store']);
  Route::post('/companies/{company_id}/requests/{request_id}/confirm_via_email', 'CompanyRequestsController@confirmViaEmail');
  Route::post('/companies/{company_id}/requests/{request_id}/confirm_via_phone', 'CompanyRequestsController@confirmViaPhone');
  Route::post('/companies/{company_id}/requests/{request_id}/validate', 'CompanyRequestsController@validateRequest');
  Route::post('/companies/{company_id}/requests/store', ['uses' => 'CompanyRequestsController@store', 'as' => 'companies.{company_id}.requests.store']);
  Route::get('/requests/{id_hash}/locations', 'RequestsController@locations');
 
  Route::get('/requests/{id_hash}', 'RequestsController@show');
