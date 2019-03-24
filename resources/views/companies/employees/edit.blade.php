@extends ('companies/company_layout')

@section ('sub-content')
    <h4>Employee: {{ $employee->name }}</h4>
    <hr>

    {{ Form::open(['route' => ['employees.update', $company->id, $employee->id], 'method' => 'put', 'files' => true]) }}
        <div class="form-group">
            <label for="">Photo</label>
            <br>
            @if (isset($employee->profilePhoto) == 1)
                <img src="{{ $employee->profilePhoto->url }}" class="img-rounded" style="max-height: 200px; max-width: 200px">
            @else
                <img src="https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg" alt="" class="img-rounded" style="width: 140px; height: 140px">
            @endif
            <input type="file" name="photo">
        </div>
        <div class="form-group">
            <label for="">Joined LocationAware On</label>
            <br>
            {{ $employee->created_at->toDayDateTimeString() }}
        </div>
        <div class="form-group">
            <label for="">Employee Since</label>
            <br>
            <input type="date" name="employee_since" value="{{ $employee->employee_since ? $employee->employee_since->format('Y-m-d') : '' }}">
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <br>
            {{ $employee->email }}
        </div>

        <div class="form-group">
            <label for="">Trade</label>
            <p>
            <img src="/images/black-car.png" alt="">
            Engineers
            |
            <img src="/images/automobile.png" alt="">
            Independent Adjusters
            |
            <img src="/images/brown-truck.png" alt="">
            Insurance Company Adjusters
            <br>
            <img src="/images/transport.png" alt="">
            Mitigation Contractors
            |
            <img src="/images/yellow-truck.png" alt="">
            Roofing Contractors
            |
            <img src="/images/truck.png" alt="">
            General Contractors
            </p>
            {{ Form::select('trade_id', $trades, $employee->trade_id, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            <label for="">Phone Number</label>
            <br>
            <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
        </div>

        <div class="form-group">
            <label for="">Description</label>
            <br>
            <textarea name="description">{{ $employee->description }}</textarea>
        </div>

        @if ($employee->role_id != 2)
            <div class="form-group">
                <label for="">Company Role</label>
                <br>
                {{ Form::select('role_id', ['3' => 'Company Admin', '4' => 'Company Employee'], $employee->role_id) }}
            </div>
        @else
            <div class="form-group">
                <label for="">Company Role</label>
                <br>
                <p>
                    You are a super company admin.
                </p>
            </div>
       @endif
        <div class="form-group">
            <label for="">Office</label>
            <br>
            {{ Form::select('office_id', $offices, $employee->company_office_id, ['placeholder' => 'Choose an office']) }}
        </div>
        <div class="form-group">
            <button class="btn btn-success">
                Save
            </button>
        </div>
    {{ Form::close() }}

@endsection
