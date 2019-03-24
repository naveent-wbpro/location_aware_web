@extends ('layouts.request_popup')

@section ('content')
    <div class="row">
        <div class="text-center">
            <h2>One last step...</h2>
            <p class="lead">
                Before submitting your request, we need to confirm your phone number or email address.
                Please choose how you'd like to verify your request below.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 text-center">
            {{ Form::open(['url' => '/companies/'.$request->company_id.'/requests/'.$request->id.'/confirm_via_phone']) }}
                <button class="btn btn-default btn-md">
                    <i class="fa fa-phone"></i>
                    Confirm via Text
                </button>
            {{ Form::close() }}
            <br>
            {{ $request->customer_phone_number }}
        </div>
        <div class="col-xs-6 text-center">
            {{ Form::open(['url' => '/companies/'.$request->company_id.'/requests/'.$request->id.'/confirm_via_email', 'id' => 'email-confirmation-form']) }}
                <button class="btn btn-default btn-md" id="email-confirmation">
                    Confirm via Email
                </button>
            {{ Form::close() }}
            <br>
            {{ $request->customer_email }}
        </div>
    </div>
@endsection
