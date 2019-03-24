@extends ('admin.layout')

@section ('sub-content')
<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
	{{ Form::open(['route' => 'admin.users.store']) }}
		@include ('admin.users._form')
	{{ Form::close() }}
</div>
@endsection
