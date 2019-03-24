{{ Form::open(['route' => ['companies.{company_id}.networks.{network_id}.companies.store', $company_id, $network_id]]) }}
	<table class="table table-striped">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th># of Employees</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($companies as $company)
				<tr>
					<td>
						<input type="checkbox" id="company-{{ $company->id }}" name="company_ids[]" value="{{ $company->id }}">
					</td>
					<td>
						<label for="company-{{ $company->id }}">	
							{{ $company->name }}
						</label>
					</td>
					<td>{{ $company->employeesCount }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-right">
		<button class="btn btn-success">
			Invite Company to Network
		</button>
	</div>
{{ Form::close() }}
