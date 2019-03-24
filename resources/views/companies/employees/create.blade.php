@extends ('companies/company_layout');

@section ('sub-content')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <p class="lead">
                Create a new employee
            </p>
            <p>
                By creating a new employee, you will be adding an employee to your company and he will be visible on
                your maps.
                Your new employee will only have to use his email to login to the application.
            </p>
            {{ Form::open(['route' => ['employees.store', $company->id]]) }}
            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Employee Name">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="phone" placeholder="Phone Number">
            </div>

            <div class="form-group">
                <input class="form-control" type="text" name="email" placeholder="Employee Email">
            </div>

            <div class="form-group">
                <label for="">Description</label>
                <textarea id="" class="form-control" name="description"></textarea>
            </div>

            <div class="form-group">
                <label for="">Trade</label>
                {{ Form::select('trade_id', $trades, null, ['class' => 'form-control']) }}
            </div>
            
            <div class="form-group">
                <label for="">Company Role</label>
                <br>
                {{ Form::select('role_id', ['3' => 'Company Admin', '4' => 'Company Employee'], '', ['class' => 'form-control']) }}
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <a href="/companies/{{ $company->id }}/employees" class="btn btn-default">
                        Back
                    </a>
                </div>
                <div class="col-xs-6 text-right">
                    <button class="btn btn-success">
                        Save
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
