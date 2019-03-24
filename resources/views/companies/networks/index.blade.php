@extends ('companies/company_layout')

@section ('sub-content')
    <a href="/companies/{{ $company->id }}/networks/create" class="btn btn-default btn-sm">
        Create New Network
    </a>

    <hr>

    @foreach ($company->invitationsPending as $invitation)
        <div class="well well-sm">
            <div class="row">
                <div class="col-xs-1">
                    {{ Form::open(['route' => ['companies.{company_id}.networks.{network_id}.companies.update', $company->id, $invitation->network->id, $invitation->id], 'method' => 'put']) }}
                        <input type="hidden" name="decision" value="accept">
                        <button class="btn btn-default btn-sm">
                            Join
                        </button>
                    {{ Form::close() }}
                </div>
                <div class="col-xs-1">
                    {{ Form::open(['route' => ['companies.{company_id}.networks.{network_id}.companies.update', $company->id, $invitation->network->id, $invitation->id], 'method' => 'put']) }}
                        <input type="hidden" name="decision" value="accept">
                        <button class="btn btn-danger btn-sm">
                            Reject
                        </button>
                    {{ Form::close() }}
                </div>
                <div class="col-xs-10">
                    You've been invited to join the '{{ $invitation->network->name }}' network. 
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    @endforeach


    <table class="table">
        <thead>
            <tr>
                <th>Network Name</th>
                <th>Size</th>
                <th>Owner</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($company->allNetworks as $network)
            <tr>
                <td>
                    @if ($network->company_id == $company->id)
                        <a href="/companies/{{ $company->id }}/networks/{{ $network->id }}">
                            {{ $network->name }}
                        </a>
                    @else
                        {{ $network->name }}
                    @endif
                </td>
                <td>{{ $network->companiesCount }} companies</td>
                <td>
                    <span class="{{ $network->owner->id == $company->id ? 'text-success' : '' }}">
                        {{ $network->owner->name }}
                    </span>
                </td>
                <td class="text-right">
                    @if ($network->owner->id == $company->id)
                        {{ Form::open(['route' => ['networks.destroy', $company->id, $network->id], 'method' => 'delete', 'class' => 'confirm-delete']) }} 
                            <button class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                                Delete
                            </button>
                        {{ Form::close() }}
                    @else
                        {{ Form::open(['route' => ['companies.{company_id}.networks.{network_id}.companies.leave', $company->id, $network->id], 'method' => 'delete', 'class' => 'confirm-delete']) }}
                            <button class="btn btn-danger btn-xs">
                                <i class="fa fa-sign-out"></i>
                                Leave
                            </button>
                        {{ Form::close() }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
