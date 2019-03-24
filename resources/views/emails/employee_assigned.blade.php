Hello {{ $user->name }},<br>

<p>
    You have been assigned to a new job.
</p>

<p>
    Your job is located at {{ $request->customer_address }}.
    {{ $request->customer_name }} is the customer you will be working with.
    You can reach {{ $request->customer_name }} at {{ $request->customer_phone_number }}.
</p>

<p>
    <a href="{{ url("companies/{$request->company_id}/requests/{$request->id}/acknowledge?user_id={$user->id}") }}">
        Click here to acknowledge receipt of the assignment
    </a>
</p>
