Hello {{ $request->customer_name }},<br>

<p>
    {{ $request->company->name }} has assigned a service worker to your request. You will receive an email when your service worker is heading your way.
</p>

<hr>

@if (!empty($user->profilePhoto))
    <h2>{{ $user->name }}</h2>
    <img src="{{ $user->profilePhoto->url }}" alt="" style="max-height: 140px; max-height: 140px">
    <br>
    @if ($user->employee_since)
        <b>Employee since</b>
        <br>
        {{ $user->employee_since->toFormattedDateString() }}
    @endif
    @if ($user->description)
        <p>
            {{ $user->description }}
        </p>
    @endif
@endif
