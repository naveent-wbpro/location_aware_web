@extends ('layouts.app')

@section ('content')
    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ active_class(if_uri(['admin']), 'active') }}">
            <a href="/admin">
                Overview
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/users']), 'active') }}">
            <a href="/admin/users" >
                Users
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/companies']), 'active') }}">
            <a href="/admin/companies" >
                Companies
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/requests']), 'active') }}">
            <a href="/admin/requests">
                Requests
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/networks']), 'active') }}">
            <a href="/admin/networks">
                Networks
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/billing']), 'active') }}">
            <a href="/admin/billing">
                Billing
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/email_manager']), 'active') }}">
            <a href="/admin/email_manager">
                Email Manager
            </a>
        </li>
        <li role="presentation" class="{{ active_class(if_uri(['admin/ads']), 'active')}}">
            <a href="/admin/ads">
                Ads
            </a>
        </li>
    </ul>

    <div class="sub-content">
        @yield ('sub-content')
    </div>
@endsection
