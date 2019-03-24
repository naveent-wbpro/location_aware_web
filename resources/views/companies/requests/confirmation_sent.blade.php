@extends ('layouts.request_popup')

@section ('content')
    <div class="row">
        <div class="text-center col-xs-12 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <h2>Confirmation Sent</h2>
            <p class="lead">
                A confirmation has been sent. Please type in the code below
            </p>
            {{ Form::open(['url' => '/companies/'.$job_request->company_id.'/requests/'.$job_request->id.'/validate'])}}
                <label for="validation_string">Validation Code</label>
                <input type="text" class="form-control" id="validation_string" name="validation_string">

                <button class="btn btn-default">
                    Validate
                </button>
            {{ Form::close() }}
        </div>
    </div>
@endsection
