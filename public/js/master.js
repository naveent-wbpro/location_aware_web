jQuery(document).ready(function($){
	$('.confirm-delete').on('submit',function(e){
		if(!confirm('Do you want to delete this item?')){
			e.preventDefault();
		}
	});
});

$.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
})
