// a-folio JS scripts
jQuery(document).ready(function($){

	"use strict";

	// Collecting the original text contents of descriptions - needed for increasing tile sizes
	var orig_text_obj = {};
	$('.a-folio-tile-wrapper').each(function(){
		var id = $(this).attr('id');
		orig_text_obj[id] = {
			h1		: $('.a-folio-tile-title h1', this).text(),
			h2		: $('.a-folio-tile-title h2', this).text(),
			desc	: $('.a-folio-tile-desc', this).text()
		};
	});


	// Cuts the text to be no higher than the specified max height
	function a_folio_cut_text( orig_obj ) {

		if ( typeof orig_obj == 'undefined' ) {
			orig_obj = false;
		}

		$( '.a-folio-tile-wrapper' ).each(function(){

			if ( $(this).is('.a-folio-tile-size-1_3') ) {
				var max_content_height = 150;
			}
			else {
				var max_content_height = 170;
			}

			if ( orig_obj !== false ) {
				$('.a-folio-tile-desc', this).text(orig_obj[$(this).attr('id')].desc);
				$('.a-folio-tile-title h1', this).text(orig_obj[$(this).attr('id')].h1);
				$('.a-folio-tile-title h2', this).text(orig_obj[$(this).attr('id')].h2);
			}

			// Setting up the description height ...
			if ( $('.a-folio-tile-desc', this).height() > max_content_height ) {

				$('.a-folio-tile-desc', this).each(function(){
					while ( $(this).height() > max_content_height ) {
						var str = $(this).text();
						var lastIndex = str.lastIndexOf(" ");
						str = str.substring( 0, lastIndex ) + "&nbsp;&hellip;";
						jQuery(this).html(str);
					}
				});

			}

			// The tile title height ...
			if ( $('.a-folio-tile-title h1', this).height() >= 60 ) {

				$('.a-folio-tile-title h1', this).each(function(){
					while ( $(this).height() >= 60 ) {
						var str = $(this).text();
						var lastIndex = str.lastIndexOf(" ");
						str = str.substring( 0, lastIndex ) + "&hellip;";
						jQuery(this).html(str);
					}
				});

			}

			// And the tile subtitle height ...
			if ( $('.a-folio-tile-title h2', this).height() >= 30 ) {

				$('.a-folio-tile-title h2', this).each(function(){
					while ( $(this).height() >= 30 ) {
						var str = $(this).text();
						var lastIndex = str.lastIndexOf(" ");
						str = str.substring( 0, lastIndex ) + "&hellip;";
						jQuery(this).html(str);
					}
				});

			}

		});

	}

	a_folio_cut_text();

	// Re-init the text length when the tiles are resized
	$(window).resize(function(){
		a_folio_cut_text( orig_text_obj );
	});

	// Try to detect touchscreens
	function a_folio_is_touch_device() {
		return 'ontouchstart' in window || navigator.maxTouchPoints;
	};

	// Do the touch-based animations on touch devices
	if ( a_folio_is_touch_device() ) {

		$('.a-folio').addClass('touch');

		$('.a-folio-tile').on('click', function(){
			if ( $('.a-folio-tile-cover', this).is('.touch-clicked') ) {
				$('.a-folio-tile-cover', this).removeClass('touch-clicked');
			}
			else {
				$('.a-folio-tile-cover', this).addClass('touch-clicked');
			}
		});

	}

});
