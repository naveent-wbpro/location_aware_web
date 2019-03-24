@extends ('companies.company_layout')

@section ('sub-content')
    <p class="lead">
        Week of {{ \Carbon\Carbon::now()->subWeek()->toFormattedDateString() }} and {{ \Carbon\Carbon::now()->toFormattedDateString() }}
    </p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Total Time with Active GPS</th>
                <th>Total Distance</th>
                <th>Assignments Handled</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($company->employees as $employee)
                <tr>
                    <td>
                        <a href="/companies/{{ $company->id }}/reports/{{ $employee->id }}">
                            {{ $employee->name }}
                        </a>
                    </td>
                    <td>
                        {{ $employee->totalActiveGpsTime(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now()) }}
                    </td>
                    <td>
                        {{ number_format($employee->totalDistanceTravelled(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now())) }} miles
                    </td>
                    <td class="text-center">
                        {{ $employee->assignmentsBetween(\Carbon\Carbon::now()->subWeek(), \Carbon\Carbon::now())->count() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
