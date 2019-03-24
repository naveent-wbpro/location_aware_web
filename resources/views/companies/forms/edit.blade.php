@extends ('companies/company_layout')

@section ('sub-content')
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <p class="lead">
                Edit form
            </p>
            {{ Form::open(['route' => ['companies.{company_id}.forms.update', $company->id, $form->id], 'method' => 'put']) }}
                @include ('companies.forms._form')
            {{ Form::close() }}
        </div>
    </div>
@endsection
