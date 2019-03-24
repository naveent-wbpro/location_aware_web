@extends ('admin.layout')

@section ('sub-content')
    <h4>Total Companies</h4>
    {{ $companies_count }}

    <h4>Total Users</h4>
    {{ $users_count }}

    <h4>New Users in last 30 days</h4>
    {{ $new_users }}

    <h4>Open Requests</h4>
    {{ $open_requests }}

    <h4>Active mobile users in last 24 hours</h4>
    {{ $active_mobile_units }}

@endsection
