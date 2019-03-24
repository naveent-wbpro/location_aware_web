@extends('layouts.app')

@section('content')
    <h3>Edit {{ $company->name }} public profile</h3>

    <hr>

    {{ Form::open(['route' => ['companies.{company_id}.update', $company->id], 'files' => true]) }}
        <div class="col-xs-12 col-sm-push-6 col-sm-6">
            <div class="well well-sm">
                <h4>
                    Credentialing
                </h4>
                <p>
                    Customers want to be assured in today's world that they are dealing with honest, knowledgeable, experienced and reputable contractors. We know you are but want to share with the world and let them know as well. Get certified Today to make an impression tomorrow.  
                </p>

                <a href="https://form.jotform.com/51586495822163" class="btn btn-default">
                        Click here to get credentialed
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-pull-6 col-sm-6">
            <div class="form-group">
                <label for="description">Company Description</label> 
                <i class="fa fa-question" title="The full name you want to use to identify your company"></i>
                <textarea name="description" class="form-control" placeholder="Your company description">{{ $company->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="description">Company Image</label>
                @if ($company->photo)
                    <a class="form-group">
                        <img class="media-object" src="{{ $company->photo->url }}" alt="" style="max-width: 120px; max-height: 120px">
                    </a>
                @endif
            <input type="file" name="photo">
            </div>
            <div class="form-group">
                <label for="description">Company Trade</label>
                <i class="fa fa-question" title="Select one or more trades you want associated with your company"></i>
                {{ Form::select('trade_id', $trades, $company->trade_id, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <label for="description">Company Email</label>
                {{ Form::text('email', $company->email, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <label for="description">Company Website</label>
                {{ Form::text('website', $company->website, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <label for="description">Company Phone Number</label>
                {{ Form::text('phone_number', $company->phone_number, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <label for="description">Company Address</label>
                {{ Form::text('street_address', $company->street_address, ['class' => 'form-control', 'placeholder' => 'Street Name']) }}
            </div>
            <div class="form-group">
                {{ Form::text('city', $company->city, ['class' => 'form-control', 'placeholder' => 'City']) }}
            </div>
            <div class="form-group">
                {{ Form::text('zip_code', $company->zip_code, ['class' => 'form-control', 'placeholder' => 'Zip Code']) }}
            </div>
            <div class="form-group">
                {{ Form::select('state', $states, $company->state, ['placeholder' => 'Pick your state or province', 'class' => 'form-control']) }}
            </div>

            <div class="form-group">
                <label for="">Years in Experience</label>
                <i class="fa fa-question" title="Years your company has been in business or years the owner has been in the trade."></i>
                {{ Form::select('years_in_business', range(0,100), $company->years_in_business, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <label for="">Contractor Warranty</label>
                <p>
                
                <small>
                    We require a 'Certificate of Dry' be provided by all water mitigation contractors to the property owner at the job completion.
                    We also require at least 1 year contractor warranty on workmanship for General or Restoration work.
                </small>
                </p>
                <small>By clicking "Yes" you agree to provide the Certificate of Dry and the Contractor Warranty.</small>
                {{ form::select('contractor_warranty', ['No', 'Yes'], $company->contractor_warranty !== null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <label for="description">Map for Request</label>
                <p>
                    You can add a map to your public profile. To add a map, select a map you have created from the dropdown below.
                </p>
                {{ Form::select('code_snippet_website_id', $available_maps, $company->code_snippet_website_id, ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-xs-6 col-md-3">
            <a href="/" class="btn btn-default">
                Back
            </a>
        </div>
        <div class="col-xs-6 col-md-3 text-right">
            <button class="btn btn-success">
                Save
            </button>
        </div>
        <hr>
        
    {{ Form::close() }}
@endsection
