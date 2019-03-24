@extends ('companies/company_layout')

@section ('sub-content')
    <a class="btn btn-default btn-sm" href="/companies/{{ $company->id }}/offices/create">
    Create New Office
</a>

<div class="clearfix"></div>
<hr>

<table class="table">
    <thead>
        <tr>
            <th>Office Name</th>
            <th>Address</th>
            <th>Timezone</th>
            <th class="text-center">Address Found</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($company->offices as $office)
            <tr>
                <td>
                    <a href="/companies/{{ $company->id }}/offices/{{ $office->id }}/edit">
                        {{ $office->name }}
                    </a>
                </td>
                <td>
                    {{ $office->street }}, {{ $office->city }}
                    
                </td>
                <td>
                    {{ $office->timezone or 'N/A' }}
                </td>
                <td class="text-center">
                    @if (!empty($office->latitude) && !empty($office->longitude))
                        <i class="fa fa-check-circle text-success"></i>
                    @else
                        <i class="fa fa-times-circle text-danger"></i>
                    @endif
                </td>
                <td class="text-right">
                    {{ Form::open(['route' => ['offices.destroy', $company->id, $office->id], 'method' => 'delete', 'class' => 'confirm-delete']) }}
                        <button class="btn btn-danger btn-xs">
                            <i class="fa fa-times"></i>
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection


