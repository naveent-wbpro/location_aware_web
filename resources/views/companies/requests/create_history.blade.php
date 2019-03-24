@extends ('companies.company_layout')

@section ('sub-content')
    {{ Form::open(['url' => '/companies/'.$company->id.'/requests/history']) }}
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label for="created-at">Date</label>
                    <input type="date" class="form-control" name="date" id="created-at">
                </div>

                <div class="form-group">
                    <label for="customer-name">Customer Name</label>
                    <input id="customer-name" type="text" name="customer_name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="customer-email">Customer Email</label>
                    <input id="customer-email" type="email" name="customer_email" class="form-control">
                </div>

                <div class="form-group">
                    <label for="customer-phone-number">Customer Phone Number</label>
                    <input id="customer-phone-number" type="text" name="customer_phone_number" class="form-control"> 
                </div>

                <div class="form-group">
                    <label for="customer-address">Customer Address</label>
                    <input type="text" id="customer-address" class='form-control' name="customer_address">
                </div>

                <div class="form-group">
                    <label for="customer-city">Customer City</label>
                    <input id="customer-city" class="form-control" type="text" name="customer_city">
                </div>

                <div class="form-group">
                    <label for="customer-state">Customer State</label>
                    <input id="customer-state" class="form-control" type="text" name="customer_state">
                </div>

                <div class="form-group">
                    <label for="customer-zipcode">Customer Zipcode</label>
                    <input id="customer-zipcode" class="form-control" type="text" name="customer_zipcode">
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <a class="btn btn-default" href="/companies/{{ $company->id }}/requests/history">
                            Cancel
                        </a>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button class="btn btn-success">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@endsection
