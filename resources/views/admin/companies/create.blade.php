@extends ('admin.layout')

@section ('sub-content')
<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    {{ Form::open(['route' => 'admin.companies.store']) }}
        @include ('admin.companies._form')
    {{ Form::close() }}
</div>
@endsection
