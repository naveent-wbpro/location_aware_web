@extends ('companies.company_layout')

@section ('sub-content')
    <p class="lead">
        Customer Name: {{ $request->customer_name }}
    </p>

    <div class="form-group">
        <b>Elapsed Time Since Request :</b>
        <br>
        {{ $request->created_at->diffForHumans() }} - {{ $request->created_at->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}
    </div>

    @if ($request->scheduled_date != null)
        <div class="form-group">
            <b>This customer has requested service at a future date</b>
            <br>
            {{ $request->scheduled_date->format('F jS, Y') }} from {{ $request->timeWindow->description }}
        </div>
    @endif

    <div class="form-group">
        <b>Your employees near the job site :</b>
        <br>
        @forelse ($company->employeeLocations() as $employee)
            {{ $employee['name'] }} - {{ $request->distanceFrom($employee['latitude'], $employee['longitude']) }} miles away
        @empty
            You have no employees available
        @endforelse
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            {{ Form::open(['route' => ['companies.{company_id}.requests.claim', $company->id, $request->id]]) }}
                <button class="btn btn-lg btn-success">
                    Claim this job for ${{ $price->amount }}
                </button>
            {{ Form::close() }}
        </div>
    </div>
@endsection
