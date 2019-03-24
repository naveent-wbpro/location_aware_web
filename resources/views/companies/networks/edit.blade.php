@extends ('companies/company_layout');

@section ('sub-content')
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-2">
			<p class="lead">
				Edit a new network
			</p>
			<p class="text-muted">
				Networks allow you to team up with other companies and have a broader reach on a map. 
				After creating a network, you can create a code snippet for everyone in that network.
			</p>
      {{ Form::open(['route' => ['networks.update', $company->id, $network->id], 'method' => 'put']) }}
				<div class="form-group">
                                    <input class="form-control" type="text" name="name" placeholder="Network Name" value="{{ $network->name }}">		
				</div>

				<div class="form-group">
                                    <textarea class="form-control" name="description" placeholder="Description">{{ $network->description }}</textarea>
				</div>

				<div class="row">
					<div class="col-xs-6">
						<a href="/companies/{{ $company->id }}/networks" class="btn btn-default">
							Back
						</a>
					</div>
					<div class="col-xs-6 text-right">
						<button class="btn btn-success">
							Save
						</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@endsection
