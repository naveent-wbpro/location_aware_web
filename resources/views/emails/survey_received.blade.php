Hello {{ $admin->name }}, <br>

<p>
    You received a new survey response from {{ $request->customer_name }}.
    {{ $request->customer_name }} left the following review.
</p>

<p>
    @if ($request->survey_result === 1)
        Your work was fabulous
    @elseif ($request->survey_result === 2)
        Your work was good
    @elseif ($request->survey_result === 3)
        Your work was just OK
    @elseif ($request->survey_result === 4)
       I was really disappointed with your work
    @endif

    {{ $request->survey->comment }}
</p>
