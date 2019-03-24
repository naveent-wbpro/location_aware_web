@extends ('companies/company_layout');

@section ('sub-content')
<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2">
        <p class="lead">
            Create a new Api Token
        </p>
        {{ Form::open(['route' => ['api_tokens.store', $company->id]]) }}
            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Name to identify your api token by" value="{{ old('name') }}">		
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <a href="/companies/{{ $company->id }}/api_tokens" class="btn btn-default">
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
