<?php
// Page Builder app for Themple-based themes



// a-folio PB App
add_action ( 'init', function() {

	if ( function_exists( 'tpl_register_pb_app' ) ) {
		tpl_register_pb_app( array(
			'name'		=> 'a_folio',
			'title'		=> __( 'Portfolio (a-folio)', 'a-folio' ),
			'class'		=> 'TPL_PB_Afolio',
			'pos'		=> 65,
		) );
	}

}, 20 );


class TPL_PB_Afolio {


	// The extra admin fields added by this app
	public function get_admin_fields() {

		return array(
			array(
				"name"			=> 'a_folio_pb_unique_pattern',
				"title"			=> __( 'Unique pattern', 'a-folio' ),
				"description"	=> __( 'If no, the pattern from Settings » a-folio Settings will be used.', 'a-folio' ),
				"type"			=> 'select',
				"values"		=> array(
					"no"			=> __( 'No', 'a-folio' ),
					"yes"			=> __( 'Yes', 'a-folio' ),
				),
				"key"			=> true,
				"default"		=> 'no',
			),
			array(
				"name"			=> 'a_folio_pb_row_pattern',
				"title"			=> __( 'Tile rows pattern', 'a-folio' ),
				"description"	=> __( 'How should the tiles be arranged inside the consecutive rows? If you add more lines here, the different row patterns will follow each other in the front end.', 'a-folio' ),
				"type"			=> 'select',
				"values"		=> array(
					"l2"			=> __( '2 tiles / line', 'a-folio' ),
					"l3"			=> __( '3 tiles / line', 'a-folio' ),
					"l4"			=> __( '4 tiles / line', 'a-folio' ),
					"s14"			=> __( '1 big + 4 small', 'a-folio' ),
					"s41"			=> __( '4 small + 1 big', 'a-folio' ),
				),
				"repeat"		=> true,
				"key"			=> true,
				"admin_class"	=> 'a-folio-row-pattern',
				"condition"		=> array(
					array(
						"type"		=> 'option',
						"name"		=> '_THIS_/a_folio_pb_unique_pattern',
						"relation"	=> '=',
						"value"		=> 'yes',
					),
				),
			),
			array(
				"name"			=> 'a_folio_pb_pattern_num',
				"title"			=> __( 'Pattern repetitions', 'a-folio' ),
				"description"	=> __( 'Maximum repetitions of the pattern', 'a-folio' ),
				"type"			=> 'number',
				"min"			=> 0,
			),
			array(
				"name"			=> 'a_folio_pb_other_params',
				"title"			=> __( 'Other parameters', 'a-folio' ),
				"description"	=> __( 'You can add more shortcode parameters here. Just use the param="value" syntax and separate them with a space.', 'a-folio' ),
				"type"			=> 'text',
			),
		);

	}


	public function frontend_value( $values = array() ) {

		$result = '';

		$result .= '[a-folio';

		if ( isset( $values["a_folio_pb_pattern_num"] ) ) {
			$result .= ' pattern_num="' . $values["a_folio_pb_pattern_num"] . '"';
		}

		if ( isset( $values["a_folio_pb_row_pattern"] ) && $values["a_folio_pb_unique_pattern"] == 'yes' ) {
			$result .= ' pattern="';
			if ( is_array( $values["a_folio_pb_row_pattern"] ) ) {
				foreach ( $values["a_folio_pb_row_pattern"] as $row ) {
					$result .= $row . '-';
				}
			}
			else {
				$result .= 'l2';
			}
			$result = rtrim( $result, '-' ) . '"';
		}

		$result .= ' ' . $values["a_folio_pb_other_params"];

		$result .= ']';

		return do_shortcode( $result );

	}


}
