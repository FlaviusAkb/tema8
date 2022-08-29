(function($) {
	function delay(callback, ms) {
		var timer = 0;
		return function() {
			var context = this, args = arguments;
			clearTimeout( timer );
			timer = setTimeout(
				function () {
					callback.apply( context, args );
				},
				ms || 0
			);
		};
	}

	$( '#wpr-filter select' ).on( 'change', WprDoAjax );

	$( '#wpr-filter input#keyword' ).keyup( delay( WprDoAjax, 500 ) );

	function WprDoAjax(){

		var regions = $( '#wpr-filter select' ).val();
		var search  = $( '#wpr-filter input#keyword' ).val();
		data        = {
			action: 'search',
			regions: regions,
			keyword: search,
		}
		$.ajax(
			{
				url: WPR.ajax_url,
				type: 'GET',
				data: data,
				// dataType: 'json',
				success: function(response){
					console.log( response );
					$( '#software-archive' ).empty();
					var content = '';
					jQuery.each(
						response,
						function(i, f){
							content += '<div class="software-card-simple">';
							content += '<div class="software-card-simple-title-container">';
							content += '<h2 class="scs-title">';
							content += '<a href="' + f.link + '">' + f.title + '</a>';
							content += '</h2>';
							content += '</div>';
							content += '</div>';
						}
					);

					jQuery( "#software-archive" ).html( content );

				}
			}
		)
	}

} )( jQuery );
