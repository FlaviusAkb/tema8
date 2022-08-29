function fetch(){

	jQuery.ajax({
		url: WPR.ajax_url,
		type: 'post',
		data: { action: 'data_fetch', keyword: jQuery('#keyword').val() },
		success: function(data) {
			jQuery('#software-archive').html( data );
		}
	});

}
