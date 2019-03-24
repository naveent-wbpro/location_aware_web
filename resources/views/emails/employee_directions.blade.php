
<h4>You have acknowledged the assignment .</h4>

<div class="panel-heading">
    <h5 class="panel-title">Assignment Information</h5>
</div>

<dl>
    <dt><b>Customer Name</b></dt>
    <dd>{{ $request->customer_name }}</dd>

    <dt><b>Phone Number</b></dt>
    <dd>{{ $request->customer_phone_number_type or '' }} {{ $request->customer_phone_number }}</dd>

    <dt><b>Email</b></dt>
    <dd>{{ $request->customer_email }}</dd>

    <dt><b>Address</b></dt>
    <dd>{{ $request->customer_address }}</dd>

    <dt><b>Directions to Customer</b></dt>
    <dd>
        <a href="{{ "companies/{$request->company_id}/requests/{$request->id}/on_the_way?user_id={$user->id}" }}">
            Click here for directions when you're heading out to the job site.
        </a>   
    </dd>
</dl>
