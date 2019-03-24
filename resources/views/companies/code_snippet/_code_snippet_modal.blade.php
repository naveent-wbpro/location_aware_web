<!-- Modal -->
<div class="modal fade" id="code-snippet-modal-{{ $code_snippet->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Your Code Snippet</h4>
			</div>
			<div class="modal-body">
				<p>
					The following is a code snippet to add a map to your website. 
					Simply copy the below code into a portion on your website and you'll be off and running. 
					Your employees will automatically be updated on the map as they move around.
					The map will auto resize to fit the parent element it is put in.
				</p>
				<textarea class="form-control" rows="20" style="resize: none">@include ('companies/code_snippet/_code_snippet_code')</textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
