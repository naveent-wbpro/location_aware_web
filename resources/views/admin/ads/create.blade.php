@extends ('admin.layout')

@section ('sub-content')
    {{ Form::open(['route' => 'admin.ads.store']) }}
        @include ('admin.ads._form')

        <div class="form-group">
            <div class="row">
                <div class="col-xs-6">
                    <a class="btn btn-default" href="/admin/ads">
                        Cancel
                    </a>
                </div>
                <div class="col-xs-6 text-right">
                    <button class="btn btn-success">
                        Save
                    </button>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@endsection
