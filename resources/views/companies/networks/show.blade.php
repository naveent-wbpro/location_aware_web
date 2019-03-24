@extends ('companies/company_layout')

@section ('sub-content')
    <div class="row">
        <div class="col-xs-6">
            <p class="lead">
                    {{ $network->name }}
                    <a href="/companies/{{ $company->id }}/networks/{{ $network->id }}/edit" class="btn btn-default btn-xs">
                            <i class="fa fa-edit"></i>
                            Edit
                    </a>
            </p>
        </div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-default btn-sm" href="/companies/{{ $company->id }}/networks/{{ $network->id }}/map">
                <i class="fa fa-map-marker"></i>
                View Map
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 text-center">
            <div class="well well-sm lead">
                <p class="lead">Companies</p>
                {{ $network->companiesCount }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 text-center">
            <div class="well well-sm lead">
                <p class="lead">Requests This Month</p>    
                {{ $network->networkRequestsThisMonth->count() }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 text-center">
            <div class="well well-sm lead">
                <p class="lead">Total Employees</p>
                {{ $network->employeesCount }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 text-center">
            <div class="well well-sm lead">
                <p class="lead">Active Employees</p>
                {{ $network->activeEmployeesCount }}
            </div>
        </div>
    </div>

    @if ($is_network_owner)
        @if ($company->credentialed !== null)
            <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#create-company-modal">
                <i class="fa fa-plus"></i>
                Add Company
            </button>
        @else
            <div class="alert alert-warning">
                You must first be credentialed before you can invite other companies into your network.
            </div>
        @endif
    @endif

    @include ('companies.networks._create_company_modal')

    <table class="table">
        <thead>
            <tr>
                <th>Company Members</th>
                <th class="text-center">Number of Employees</th>
                <th class="text-center">Active Employees</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($network->companies as $network_company)
                <tr>
                    <td>
                        {{ $network_company->name }}
                        <small>
                            <a style="cursor: pointer" class="show-office" data-company-id="{{ $network_company->id }}">
                                <i class="fa fa-plus"></i>
                                View Offices
                            </a>
                        </small>
                    </td>
                    <td class="text-center">
                        {{ $network_company->employeesCount }}
                    </td>
                    <td class="text-center">
                        {{ $network_company->activeEmployeesCount }}
                    </td>
                    <td>
                        @if($is_network_owner && $network_company->id != $company->id)
                            {{ Form::open(['route' => ['companies.{company_id}.networks.{network_id}.companies.destroy', $company->id, $network->id, $network_company->id], 'method' => 'delete', 'class' => 'confirm-delete']) }}
                                <button class="btn btn-xs btn-danger">
                                    <i class="fa fa-times"></i>
                                </button>
                            {{ Form::close() }}
                        @endif
                    </td>
                </tr>
                <tr id="{{ $network_company->id }}" style="display: none">
                    <td colspan="3">
                        <div class="well well-sm">
                            <p>
                                <b>Offices</b>
                            </p>

                            @forelse ($network_company->offices as $office)
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <address>
                                        <strong>{{ $office->name }}</strong><br>
                                        {{ $office->street }}<br>
                                        {{ $office->city }}, 
                                        {{ $office->state }}
                                        {{ $office->zip_code }}
                                    </address>

                                </div>
                            @empty
                                No offices
                            @endforelse
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
            @foreach($pending_invitations as $invitation)
                <tr class="warning">
                    <td>{{ $invitation->company->name }}</td>
                    <td>{{ $invitation->company->employeesCount }}</td>
                    <td>Invite Sent</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <script>
        $(document).on("click", ".show-office", function() {
            company_id = $(this).data('company-id');        

            $("#"+company_id).toggle();
        })
    </script>
@endsection
