@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row">
        @if($company->hasPendingInvitations)
            <div class="col-xs-12 col-md-6 col-md-offset-3">
            <div class="alert alert-success">
                You have pending network invitations
            </div>
        </div>
        <div class="clearfix"></div>
    @endif
    <div class="col-md-10 col-md-offset-1">
        <h3>
            {{ $company->name }} Dashboard
        </h3>
        <h5>
            <a href="/companies/{{ $company->id }}">
                Open Company Profile
            </a>
        </h5>
    </div>
    <div class="clearfix"></div>
    <div class="col-xs-12 col-md-10 col-md-offset-1">
        <ul class="nav nav-tabs">
            <li>
                <a href="/companies/{{ $company->id }}/requests">
                    Requests
                    <span id="requests-count"></span>
                    <i class="fa fa-exclamation-circle text-danger" id="requests-alert" style="display: none;"></i>
                </a>
            </li>

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/offices']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/offices">Offices</a>
                </li>
            @endif

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/employees*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/employees">Employees</a>
                </li>
            @endif

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/networks*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/networks">Networks</a>
                </li>
            @endif

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/reports*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/reports">Reports</a>
                </li>
            @endif

            @if (user_is('company_employee'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/calendar*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/calendar">Calendar</a>
                </li>
            @endif

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/maps*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/maps">Maps</a>
                </li>
            @endif

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/code_snippet*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/code_snippet">Code Snippet</a>
                </li>
            @endif

            @if (user_is('company_admin'))
                <li role="presentation" class="{{ active_class(if_uri_pattern(['companies/*/api_tokens*']), 'active') }}">
                    <a href="/companies/{{ $company->id }}/api_tokens">Api Tokens</a>
                </li>
            @endif
        </ul>
        <div class="sub-content">
            @yield ('sub-content')
        </div>	
    </div>
</div>
</div>
<script src="/js/company.js"></script>
@endsection
