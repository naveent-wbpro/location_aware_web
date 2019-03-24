@extends ('layouts/app')

@section ('content')
    <h4>You have acknowledged the assignment .</h4>

    <div class="panel-heading">
        <h3 class="panel-title">Assignment Information</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label for="">Name</label>
            <br>
            {{ $request->customer_name }}
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <br>    
            {{ $request->customer_phone_number_type or '' }} {{ $request->customer_phone_number }}
        </div>
        <div class="form-group">
            <label>Email</label>
            <br>
            {{ $request->customer_email }}
        </div>
        <div class="form-group">
            You will receive a followup email with directions to your customer.
        </div>
    </div>
@endsection
