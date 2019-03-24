@extends ('companies/company_layout');

@section ('sub-content')
    <div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2">
        <p class="lead">
            Create a new Office Location
        </p>
        {{ Form::open(['route' => ['offices.store', $company->id]]) }}
            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Name of your office" value="{{ old('name') }}">		
            </div>

            <div class="form-group">
                <input class="form-control" type="text" name="street" placeholder="Street Address" value="{{ old('street') }}">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="city" placeholder="City" value="{{ old('city') }}">
            </div>

            <div class="form-inline form-group">
                <input class="form-control" type="text" class="form-control" name="zip_code" placeholder="Zip Code or Postal Code" value="{{ old('zip_code') }}">
                {{ Form::select('state', $states, old('state'), ['placeholder' => 'Pick your state or province', 'class' => 'form-control']) }}
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
