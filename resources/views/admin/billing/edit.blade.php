@extends ('admin.layout')

@section ('sub-content')
    <h3>Price for {{ $price->name }}</h3>
    {{ Form::open(['route' => ['admin.billing.update', $price->id], 'method' => 'put']) }}
        @include ('admin.billing._form')
   {{ Form::close() }}
@endsection
