(function($){
	$(document).ready( function(){
		
		// Show search container when search icon is clicked
		$( '.free-search-icon-link' ).click( function(e) {
			e.preventDefault();
			var $search_container = $( '.free-search-container' );

			if ( $search_container.hasClass('et_pb_is_animating') ) {
				return;
			}

			$( '.free-du-plugin-header .et_pb_fullwidth_menu' ).removeClass( 'free-menu-visible et_pb_no_animation' ).addClass('free-menu-hidden');
			$search_container.removeClass( 'free-search-hidden et_pb_no_animation' ).addClass('free-search-visible et_pb_is_animating');
			setTimeout( function() {
				$( '.free-du-plugin-header .et_pb_fullwidth_menu' ).addClass( 'et_pb_no_animation' );
				$search_container.addClass( 'et_pb_no_animation' ).removeClass('et_pb_is_animating');
			}, 450);
			$search_container.find( 'input' ).focus();
		});
		
		// Hide search container function
		function du_hide_search() {
			if ( $( '.free-search-container' ).hasClass('et_pb_is_animating') ) {
				return;
			}

			$( '.free-du-plugin-header .et_pb_fullwidth_menu' ).removeClass( 'free-menu-hidden et_pb_no_animation' ).addClass( 'free-menu-visible' );
			$( '.free-search-container' ).removeClass('free-search-visible et_pb_no_animation' ).addClass( 'free-search-hidden et_pb_is_animating' );
			setTimeout( function() {
				$( '.free-du-plugin-header .et_pb_fullwidth_menu' ).addClass( 'et_pb_no_animation' );
				$( '.free-search-container' ).addClass( 'et_pb_no_animation' ).removeClass('et_pb_is_animating');
			}, 450);
		}
		
		// Hide search container when condition met
		$( '.free-search-close' ).click( function() {
			du_hide_search();
		});
		$( document ).mouseup( function(e) {
			var $header = $( '.free-du-plugin-header .et_pb_fullwidth_menu' );
			if ( $( '.free-du-plugin-header .et_pb_fullwidth_menu' ).hasClass('free-menu-hidden') ) {
				if ( ! $header.is( e.target ) && $header.has( e.target ).length === 0 )	{
					du_hide_search();
				}
			}
		});

	} );
})(jQuery);