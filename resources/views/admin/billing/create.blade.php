@extends ('admin.layout')

@section ('sub-content')
    <h3>Create Price</h3>
    {{ Form::open(['route' => ['admin.billing.store', $price->id]]) }}
        @include ('admin.billing._form')
    {{ Form::close() }}
@endsection
