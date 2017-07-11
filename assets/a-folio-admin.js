// a-folio admin scripts
jQuery(document).ready(function($) {

	"use strict";

	// a-folio pattern dropdown settings
	var a_folio_pattern_settings = $.extend( {
		templateSelection: a_folio_pattern_template,
		templateResult: a_folio_pattern_template,
		formatSelection: a_folio_pattern_template,
		formatResult: a_folio_pattern_template,
		minimumResultsForSearch: Infinity,
	}, Themple_Admin.basic_select2_settings );



	// Add illustration to the pattern selector
	function a_folio_pattern_template(data) {

		var structure = '<span class="tpl-columnset-structure a-folio-columnset-structure">';

		if ( data.id != undefined ) {

			// Normal branch
			if ( data.id[0] == 'l' ) {
				for ( var i = 0; i < data.id[1]; i++ ) {
					structure += '<span class="tpl-columnset-structure-part csp-1_'+ data.id[1] +'"></span>';
				}
			}

			// Tiled layout branch
			if ( data.id[0] == 's' ) {
				for ( var i = 1; i < data.id.length; i++ ) {
					if ( data.id[i] == '1' ) {
						structure += '<span class="tpl-columnset-structure-part csp-1_2"></span>';
					}
					if ( data.id[i] == '4' ) {
						structure += '<span class="tpl-columnset-structure-part csp-1_2">';
						for ( var j = 0; j < data.id[i]; j++ ) {
							structure += '<span class="csp-sub"></span>';
						}
						structure += '</span>';
					}
				}
			}

		}

		structure += '</span>';
		return structure + data.text;

	}



	// Add the pattern to the select field
	if ( $('.tpl-dt-select').length > 0 ) {
		$('#wpcontent .tpl-field.tpl-dt-select.a-folio-row-pattern select').select2( a_folio_pattern_settings );
	}

	$('body').on('click', 'button.tpl-repeat-add', function(){
		$('#wpcontent .tpl-field.tpl-dt-select.a-folio-row-pattern select').select2( a_folio_pattern_settings );
	});


});
