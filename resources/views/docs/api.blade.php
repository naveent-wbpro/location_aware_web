@extends ('layouts.app')

@section ('content')
    <div class="col-xs-12 col-md-8 col-md-offset-2">
        <h3>
            Docs / API
        </h3>
        <p>
            This documentation explains how to connect with and utilize the locationaware.io API.
        </p>


        <div class="well well-sm">
            <h4>
                Authentication via Access Token
            </h4>
        </div>

        <p class="lead">1. Creating an access token</p>
        <p>
            The locationaware.io API uses api tokens that are passed on every request. 
            To get started you must login to your locationaware account and click 'API Tokens' in the navigation bar.
            You will see a list of active access tokens, if there are any available, and an option to create a new token.
            Once your new token has been created, you can use the given api token to make requests to our api.
        </p>

        <div class="well well-sm">
            <h4>
                Endpoints
            </h4>
        </div>

        <p>
            Once you have created an access token, you will be able to make requests to the locationaware api.
            <br>
            The <code>{ company_id }</code> will be given to you in the API Tokens screen to use.
        </p>

        <hr>

        <h5>Get Company Information</h5>
        <div class="form-group">
            <code>GET : /companies/{ company_id }</code>
        </div>

        <dl>
            <dt>Returns:</dt>
            <dd>
                <ul>
                    <li>id - integer</li>
                    <li>name - string</li>
                    <li>description - string</li>
                    <li>phone_number - integer</li>
                    <li>website - string</li>
                    <li>created_at - datetime</li>
                    <li>updated_at - datetime</li>
                    <li>employees - array of employees</li>
                    <li>offices - array of offices</li>
                </ul>
            </dd>
        </dl>

        <hr>

        <h5>Get Company Employee Listing</h5>
        <div class="form-group">
            <code>GET : /companies/{ company_id }/employees</code>
        </div>

        <dl>
            <dt>Returns:</dt>
            <dd>
                <ul>
                    <li>id - integer</li>
                    <li>name - string</li>
                    <li>email - string</li>
                    <li>created_at - datetime</li>
                    <li>updated_at - datetime</li>
                    <li>verified - boolean</li>
                    <li>company_id - integer</li>
                    <li>company_office_id - integer</li>
                    <li>location - location object</li>
                </ul>
            </dd>
        </dl>

        <hr>

        <h5>Create Company Employee</h5>
        <div class="form-group">
            <code>POST : /companies/{ company_id }/employees</code>
        </div>

        <dl>
            <dt>Returns:</dt>
            <dd></dd>
        </dl>

        <hr>

        <h5>Get Company Requests</h5>
        <div class="form-group">
            <code>GET : /companies/{ company_id }/requests</code>
        </div>

        <dl>
            <dt>Returns:</dt>
            <dd>
                <ul>
                    <li>id - integer</li>
                    <li>company_id - integer</li>
                    <li>employee_id - integer</li>
                    <li>is_global - bool</li>
                    <li>customer_name - string</li>
                    <li>customer_phone_number - string</li>
                    <li>customer_email - string</li>
                    <li>customer_address - string</li>
                    <li>customer_city - string</li>
                    <li>customer_state - string</li>
                    <li>customer_zipcode - string</li>
                    <li>latitude - float</li>
                    <li>longitude - float</li>
                    <li>description - string</li>
                    <li>request_origin - string</li>
                    <li>claimed_on - datetime</li>
                    <li>contacted_on - datetime</li>
                    <li>assigned_on - datetime</li>
                    <li>arrived_on - datetime</li>
                    <li>departed_on - datetime</li>
                    <li>closed_on - datetime</li>
                    <li>created_at - datetime</li>
                    <li>updated_at - datetime</li>
                </ul>
            </dd>
        </dl>
    </div>
@endsection
