@extends ('layouts.request_popup')

@section ('content')
	<div class="row">
		<div class="text-center">
			<h2>Request Received</h2>
			<p class="lead">
				A confirmation email has been sent to {{ $request->customer_email }}. {{ $request->company->name }} will contact you shortly.
			</p>
		</div>
	</div>
@endsection
