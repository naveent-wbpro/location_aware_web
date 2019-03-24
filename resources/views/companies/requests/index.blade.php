@extends ('companies.company_layout')

@section ('sub-content')
    <p>
        All times are displayed in the {{ \Auth::user()->timezone }} timezone.
    </p>
    <h5>
        Your Requests
    </h5>

    <div class="row">
        <div class="col-xs-6">
            <a href="/companies/{{ $company->id }}/requests/create?company_generated=true" class="btn btn-default btn-xs">
                Create Internal Request
            </a>
        </div>

        <div class="col-xs-6">
            <a class="pull-right btn btn-default btn-xs" href="/companies/{{ $company->id }}/requests/history">
                <i class="fa fa-clock-o"></i>
                View Closed Requests
            </a>
        </div>
    </div>
   
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Clicked On</th>
                <th>Phone Number</th>
                <th>Requested On</th>
                <th>Claimed On</th>
                <th>Assigned On</th>
            </tr>
        </thead>
        <tbody>
            @forelse($company_requests as $request)
                <tr>
                    <td>
                        <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}{{ $request->claimed_on != null ? '/edit' : ''}}">
                            {{ $request->customer_name }}
                        </a>
                    </td>
                    <td>
                        @if ($request->clicked_on === 'Instant Response')
                            <i class="fa fa-exclamation-circle text-danger"></i>
                        @endif
                        {{ $request->clicked_on }}
                    </td>
                    <td>{{ $request->customer_phone_number }}</td>
                    <td>{{ $request->created_at->timezone(\Auth::user()->timezone)->format('m/d/y h:ia T') }}</td>
                    <td>
                        @if ($request->claimed_on == null)
                            <span class="text-danger">Not Claimed</span>
                        @else
                            {{ $request->claimed_on->timezone(\Auth::user()->timezone)->format('m/d/y h:ia T') }}
                        @endif
                    </td>
                    <td>{{ !empty($request->assigned_on) ? $request->assigned_on->timezone(\Auth::user()->timezone)->format('m/d/y h:ia T') : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No direct requests available at this time.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(user_is('company_admin'))
        <hr>

        <h5>Instant Response Network Requests</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Network</th>
                    <th>Requested On</th>
                    <th>Assigned On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($network_requests as $request)
                    <tr>
                        <td>
                            @if ($request->claimed_on == null)
                                <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}">
                                    {{ $request->customer_name }}
                                </a>
                            @else
                                <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/edit">
                                    {{ $request->customer_name }}
                                </a>
                            @endif
                        </td>
                        <td>{{ $request->network->name }}</td>
                        <td>{{ $request->created_at }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No network requests available at this time.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
@endsection
