(function($) {
	// window.onload = function() {
	// 	console.log('AAAAAAAAAAAAAAAAAAAAAAA');
	//   }
	$('#wpr-filter select').on('change', function(){

		console.log('change');
		var regions = $('#wpr-filter select').val();
		data = {
			action: 'search',
			regions: regions,
		}
		$.ajax({
			url: WPR.ajax_url,
			type: 'GET',
			data: data, keyword: jQuery('#keyword').val(),
			// dataType: 'json',
			success: function(response){
				$('#software-archive').empty();
				var content = '';
				jQuery.each(response, function(i, f){
					content += '<div class="software-card-simple">';
					content += '<div class="software-card-simple-title-container">';
					content += '<h2 class="scs-title">';
					content += '<a href="' + f.link + '">'+ f.title + '</a>';
					content += '</h2>';
					content += '</div>';
					content += '</div>';
				});

				jQuery("#software-archive").html(content);

			}
		})
	});

} ) (jQuery);

