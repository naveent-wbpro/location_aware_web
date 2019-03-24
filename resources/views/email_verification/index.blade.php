@extends ('layouts.app')

@section ('content')
    <h2>
        Welcome to LocationAware.io and our family of insurance claim networks
    </h2>
    <p class="lead">
        You're only a few steps away from taking advantage of the best marketing, assignment, and claim processing technology.
    </p>

    <div class="alert alert-warning">
        Your email has yet to be verified. Please check your email inbox.
    </div>

    {{ Form::open(['email_verification']) }}
        <button class="btn btn-success">
            Click Here to Resend Email
        </button>
    {{ Form::close() }}
@endsection
