@extends ('companies.company_layout')

@section ('sub-content')

    <a href="/companies/{{ $company->id }}/requests/history/create" class="btn btn-xs btn-default">
        <i class="fa fa-plus"></i>
        Add Past Job Request
    </a>

    <p>
        Survey previous customers on work performed outside of LocationAware. 
        Click above, fill in the form, and it will display with your other past jobs with a "Send Survey" button.
        Click and a survey will be sent, tracked, and added to your list of happy clients.
    </p>

    <h5>
        Closed Requests
    </h5>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Requested On</th>
                <th>Closed On</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($company_requests as $request)
                <tr>
                    <td>
                        <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/edit">
                            {{ $request->customer_name }}
                        </a>
                    </td>
                    <td>{{ $request->created_at->timezone(\Auth::user()->timezone)->format('m/d/y h:ia T') }}</td>
                    <td>{{ $request->closed_on->timezone(\Auth::user()->timezone)->format('m/d/y h:ia T') }}</td>
                    <td>
                        @if ($request->survey_sent_at === null)
                            <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/resend_survey" class="btn btn-xs btn-default">
                                Send Survey
                            </a>
                        @endif
                        @if ($request->survey_sent_at !== null && $request->survey_result === null)
                            <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/resend_survey" class="btn btn-xs btn-default">
                                Resend Survey
                            </a>
                        @endif
                        @if ($request->survey_result !== null)
                            Survey Completed
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No Requests</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
