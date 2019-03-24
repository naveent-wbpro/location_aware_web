@extends ('companies/company_layout');

@section ('sub-content')
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <p class="lead">
                Validate a new website
            </p>
            <p>
                Validating a website ensure that nobody else is copying your code and pasting it on their own website. 
                Only validated websites will be able to retrieve correct gps coordinates.
            </p>
            {{ Form::open(['route' => ['code_snippet.update', $company->id, $code_snippet->id], 'method' => 'put']) }}
                @include ('companies.code_snippet._form')
            {{ Form::close() }}
        </div>
    </div>
@endsection
