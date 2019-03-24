@extends ('companies/company_layout');

@section ('sub-content')
    <div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2">
        <p class="lead">
            Edit an Office Location
        </p>
        {{ Form::open(['route' => ['offices.update', $company->id, $office->id], 'method' => 'put']) }}
            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Name of your office" value="{{ $office->name }}">		
            </div>

            <div class="form-group">
                <input class="form-control" type="text" name="street" placeholder="Street Address" value="{{ $office->street }}">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="city" placeholder="City" value="{{ $office->city }}">
            </div>

            <div class="form-inline form-group">
                <input class="form-control" type="text" class="form-control" name="zip_code" placeholder="Zip Code Or Postal Code" value="{{ $office->zip_code }}">
                {{ Form::select('state', $states, $office->state, ['placeholder' => 'Pick your state or province', 'class' => 'form-control']) }}
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <a href="/companies/{{ $company->id }}/offices" class="btn btn-default">
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
