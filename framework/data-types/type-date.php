<?php

// The file must have the type-[data-type].php filename format


class TPL_Date extends TPL_Data_Type {


	public	$default		= '';		// Default value if no other defaults are set


	public function __construct( $args ) {

		global $tpl_datepicker_added;

		parent::__construct( $args );

		if ( $tpl_datepicker_added !== true ) {

			add_filter( 'tpl_admin_js_strings', array( $this, 'admin_js_strings' ) );

			// Enqueue the date picker script - doing this inside the constructor prevents it to be added if no option is registered with this type
			add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {
			    wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ) );
			});
			$tpl_datepicker_added = true;

		}

	}


	// Writes the form field in wp-admin
	public function form_field_content ( $for_bank = false ) {

		echo '<div class="tpl-datatype-container">';

		if ( $this->get_option() == "" ) {
			$value = $this->default;
		}
		else {
			$value = $this->get_option();
		}

		if ( $for_bank == true ) {
			$value = $this->default;
		}

		echo '<input name="' . esc_attr( $this->form_ref() ) . '" id="' . esc_attr( $this->form_ref() ) . '" type="text" value="' . esc_attr( $value ) . '" class="tpl-date-field" autocomplete="off">';

		echo '</div>';

	}


	// Formats the option into value
	public function format_option ( $value, $args = array() ) {

		return $value;

	}


	// Strings to be added to the admin JS files
	public function admin_js_strings( $strings ) {

		$strings = array_merge( $strings, array(
			'date_starts_with'		=> get_option( 'start_of_week' ),
		) );

		return $strings;

	}


}
