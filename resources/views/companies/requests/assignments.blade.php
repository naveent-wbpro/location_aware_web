@extends ('companies.company_layout')

@section ('sub-content')
    <h4>Assign Employees to {{ $request->customer_name }}</h4>

    {{ Form::open(['route' => ['companies.{company_id}.requests.{request_id}/assignments', $company->id, $request->id], 'method' => 'put']) }}
        <table class="table">
            @foreach ($company->employees as $employee)
                <tr>
                    <td>
                    
                        <input type="checkbox" id="{{ $employee->id }}" name="employees[]" value="{{ $employee->id }}" {{ in_array($employee->id, $request->employees->pluck('id')->all()) ? 'checked' : '' }}>
                        <label for="{{$employee->id}}">
                            {{ $employee->name }}
                        </label>
                    </td>
                    <td>
                        @if (!empty($employee->location))
                                -
                            {{ $request->distanceFrom($employee->location->latitude, $employee->location->longitude) }} miles from request
                        @else
                            Not Currently Online
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="clearfix"></div>
        <div class="col-xs-6">
            <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/edit" class="btn btn-default">
                Cancel
            </a>
        </div>
        <div class="col-xs-6 text-right">
            <button class="btn btn-success">
                Save
            </button>
        </div>
    {{ Form::close() }}
@endsection
