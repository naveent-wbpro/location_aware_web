@extends ('layouts.app')

@section ('content')
    <div class="row">
    <div class="col-sm-4">
        <div class="well well-sm">
            {{ Form::open(['uri' => '', 'method' => 'get']) }}
            <p>
                <strong>Company Search</strong>
            </p>
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" value="{{ $name or '' }}">
            </div>
            <div class="form-group">
                <label for="">Insurance Specialty</label>
                {{ Form::select('trade_id', $trades, $trade_id, ['class' => 'form-control', 'placeholder' => 'All Specialties']) }}
            </div>
            <div class="form-group">
                <label for="">State</label>
                {{ Form::select('state', $states, $state, ['class' => 'form-control', 'placeholder' => 'All States']) }}
            </div>
            <div class="form-group">
                <a class="btn btn-default" href="/companies">
                    <i class="fa fa-times"></i>
                    Reset
                </a>
                <button class="btn btn-default pull-right">
                    <i class="fa fa-search"></i>
                    Search
                </button>
            </div>
            {{ Form::close() }}
        </div>
        @if (isset($code_snippet_data))
            <div style="height: 300px; width: 100%">
                <div id='location-aware-map'>LocationAware.io is loading...</div><script id='location-aware-script' data-company-id='32' data-api-key='{{ $code_snippet_data->api_key }}' src='https://newdev.locationaware.io/js/location_aware_snippet.js'></script>
            </div>
        @endif
    </div>
    <div class="col-sm-8">
        <div id="comparison-box" class="well well-sm" style="display: none">
            You have selected multiple contractors for comparison.
            <button class="pull-right btn btn-primary btn-sm" id="compare-button">
                Compare Contractors
            </button>
            <div class="clearfix"></div>
        </div>
        @forelse ($companies as $company)
            @include ('companies._snippet')
        @empty
            <div class="well well-sm">
                <i class="fa fa-thumbs-down"></i>
                No companies found with your search criteria
            </div>
        @endforelse
        {{ $companies->links() }}
    </div>

    <script>
        $(".comparison-check").on('click', function() {
            if($(".comparison-check:checked").length > 0) {
                    $("#comparison-box").show();
                } else {
                    $("#comparison-box").hide();
                }
        });

        $("#compare-button").on('click', function() {
            var array_of_ids = [];

            $(".comparison-check:checked").each(function(item) {
                array_of_ids.push($(this).val());
            })
            parameters = '';

            array_of_ids.map( function(item, key) {
                parameters += key+'='+item+'&';
            })

            window.location = '/companies/compare?'+parameters;
        })
    </script>
@endsection
