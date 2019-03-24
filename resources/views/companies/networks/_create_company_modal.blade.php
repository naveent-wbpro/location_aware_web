<!-- Modal -->
<div class="modal fade" id="create-company-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Search For Company</h4>
			</div>
			<div class="modal-body">
				<p>
					You can add companies to <strong>{{ $network->name }}</strong> by search below.
				</p>

				{{ Form::open(['route' => ['companies.{company_id}.networks.{id}.search', $company->id, $network->id], 'id' => 'search-form']) }}
					<div class="form-group">
						<label>Search</label>
						<div class="clearfix"></div>
						<span id="search-error" class="text-danger"></span>
						<input type="text" name="search_term" class="form-control" id="search-query" placeholder="Search by E-mail or Company Name">
						<small>
							<a>
								Can't find the company you're looking for?
							</a>
						</small>
					</div>
					<div class="form-group text-right">
						<button class="btn btn-success btn-sm">
							<i class="fa fa-search"></i>
							Search
						</button>
					</div>
				{{ Form::close() }}

				<div id="search-result" style="max-height: 300px; overflow: scroll;"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$("#search-form").on('submit', function(event){
		event.preventDefault();		

		object = $(this);
		serializedData = object.serializeArray();

		$.ajax({
			url: object.attr('action'),
			data: serializedData,
			method: 'post',
			beforeSend:function() {
				$("#search-error").html('');
				$(".has-error").removeClass('.has-error');
			},
			success:function(result) {
				$("#search-result").html(result);
			},
			error:function(textStatus,xhr) {
			},
			statusCode: {
				422: function(event) {
					error = JSON.parse(event.responseText)
					$("#search-error").html(error.search_term[0])
					$(".form-group").first().addClass('has-error')
				}
			}
		})
	})
</script>
