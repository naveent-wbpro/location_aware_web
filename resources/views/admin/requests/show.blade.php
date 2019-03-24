@extends ('admin.layout')

@section ('sub-content')
    <div class="col-xs-12">
        <h3>{{ $request->customer_name }}</h3>

        <hr>

        <h5>Customer Information</h5>
        <dl>
            <dt>Name</dt>
            <dd>{{ $request->customer_name }}</dd>

            <dt>Email</dt>
            <dd>{{ $request->customer_email }}</dd>

            <dt>Phone Number</dt>
            <dd>{{ $request->customer_phone_number }}</dd>

            <dt>Customer Address</dt>
            <dd>{{ $request->address }}</dd>
        </dl>

        <hr>

        <h5>Request Information</h5>
        <dl>
            <dt>Request created by</dt>
            <dd>This request was created by {{ $request->company_generated_user_id ? $requested->companyGeneratedUser->name : 'the end customer' }}.</dd>

            @if ($request->scheduled_date)
                <dt>Scheduled Date</dt>
                <dd>{{ $request->scheduled_date->format('Y-m-d') }} between {{ $request->timeWindow->description }}</dd>
            @endif

            <dt>Created At</dt>
            <dd>{{ $request->created_at }}</dd>

            <dt>Request Details</dt>
            <dd>
                @if ($request->instant_response)
                    This was an instant response request.
                @endif
                @if ($request->company)
                    This request was for <a href="/admin/companies/{{ $request->company_id }}">{{ $request->company->name }}</a>.
                @endif
                @if ($request->employee_id)
                    {{ $request->employee->name }} was the mobile user clicked on the map.
                @endif
                @if ($request->office_id)
                    {{ $request->office->name }} was the office clicked on the map.
                @endif
                @if ($request->network_id)
                    This request was made on the {{ $request->network->name }} network.
                @endif
            </dd>

            <dt>Contacted At</dt>
            <dd>{{ $request->contacted_on }}</dd>

            <dt>Assigned At</dt>
            <dd>{{ $request->assigned_on }}.</dd>

            <dt>Assigned Employees</dt>
            <dd>
                @foreach ($request->employees as $employee)
                    <a href="/admin/users/{{ $employee->id }}/edit">
                        {{ $employee->name }}
                    </a>,
                @endforeach
            </dd>

            <dt>Arrived At</dt>
            <dd>{{ $request->arrived_on or 'Has not arrived' }}</dd>

            <dt>Departed At</dt>
            <dd>{{ $request->departed_on or 'Has not departed' }}</dd>

            <dt>Closed At</dt>
            <dd>{{ $request->closed_on or 'Has not closed' }}</dd>

            <dt>Survey Sent At</dt>
            <dd>{{ $request->survey_sent_at or 'Survey not sent' }}</dd>

            <dt>Survey Result</dt>
            <dd>{{ $request->survey_result or 'No Result'}}</dd>
        </dl>
    </div>
@endsection
