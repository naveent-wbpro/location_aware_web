Hello {{ $request->customer_name }},<br>

<p>
    {{ $request->company->name }} has assigned a service worker to your request. {{ $user->name }} is on his way to your location.
</p>

<p>
    You can follow the status of your request by clicking <a href="{{ url('/requests',[$hashed_id]) }}">here</a>
</p>

<hr>

@if (!empty($user->profilePhoto))
    <h2>{{ $user->name }}</h2>
    <img src="{{ $user->profilePhoto->url }}" alt="" style="max-height: 200px; max-height: 200px">
@endif
