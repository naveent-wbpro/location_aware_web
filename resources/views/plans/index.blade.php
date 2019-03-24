@extends ('layouts.app')

@section ('content')
    <div class="col-xs-12">
        <h3>
            Plans
            <small>
                <a href="/">
                    Go Back
                </a>
            </small>
        </h3>
    </div>
    <div class="col-xs-12 col-md-8 col-md-offset-2">
        @if ($user->subscribed('main'))
            <p class="lead">
                 
            </p>
        @else
            <p class="lead">
                Not currently subscribed to a plan.
            </p>
        @endif
        @include ('plans._form')
    </div>
@endsection
