@extends ('companies/company_layout')

@section ('sub-content')
    <div class="row">
        <div class="col-xs-6">
            <a class="btn btn-default btn-sm" href="/companies/{{ $company->id }}/employees/create">
                Invite New Employee
            </a>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr>

    <table class="table">
        <thead>
            <tr>
                <th>
                    <a href="/companies/{{ $company->id }}/employees?sort=name">
                        Name
                        <i class="fa fa-arrow-down"></i>
                    </a>
                </th>
                <th>
                    <a href="/companies/{{ $company->id }}/employees?sort=office">
                        Office
                        <i class="fa fa-arrow-down"></i>
                    </a> 
                </th>
                <th>
                    <a href="/companies/{{ $company->id }}/employees?sort=role">
                        Role
                        <i class="fa fa-arrow-down"></i>
                    </a>    
                </th>
                <th>Last GPS Activity</th>
                <th></th>
            </tr>
        </thead>
        @foreach ($employees as $employee)
            <tr>
                <td>
                    <a href="/companies/{{ $company->id }}/employees/{{ $employee->id }}/edit">
                        {{ $employee->name }}
                    </a>
                </td>
                <td>{{ $employee->office->name or 'No office' }}</td>
                <td>{{ ucwords(str_replace('_', ' ' , $employee->role->name)) }}</td>
                <td>{{ !empty($employee->location) ? $employee->location->created_at->diffForHumans() : 'No recent activity' }}</td>
                <td>
                    @if ($employee->id != $user->id)
                        {{ Form::open(['route' => ['employees.destroy', $company->id, $employee->id], 'method' => 'delete', 'class' => 'confirm-delete']) }}
                            <button class="btn btn-danger btn-xs">
                                <i class="fa fa-times"></i>
                            </button>
                        {{ Form:: close() }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection


