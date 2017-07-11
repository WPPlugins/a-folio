<?php

// The file must have the type-[data-type].php filename format


class TPL_Select extends TPL_Data_Type {


	public		$key				= false;		// Should return the key (true) or the label (false)?


	public function __construct( $args ) {

		global $tpl_select_added;

		parent::__construct( $args );

		if ( $tpl_select_added !== true ) {

			add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {
				if ( !wp_script_is( 'select2' ) ) {
					wp_enqueue_script( 'tpl-select2', tpl_base_uri() . '/framework/lib/select2/js/select2.min.js', array( 'jquery' ) );
				}
				wp_enqueue_style( 'tpl-select2-style', tpl_base_uri() . '/framework/lib/select2/css/select2.min.css', array() );
			} );
			$tpl_select_added = true;

		}

	}


	// Writes the form field in wp-admin
	public function form_field_content ( $for_bank = false ) {

		echo '<div class="tpl-datatype-container">';

		// The saved or default value:
		$id = $this->get_option();

		if ( ( $id == '' || $for_bank == true ) && ( isset( $this->default ) ) ) {
			$id = $this->default;
		}

		echo '<select id="' . esc_attr( $this->form_ref() ) . '" name="' . esc_attr( $this->form_ref() ) . '" autocomplete="off">';

		if ( $this->placeholder != '' ) {
			echo '<option value="">' . esc_html( $this->placeholder ) . '</option>';
		}

		foreach ( $this->values as $key => $value ) {

			if ( is_array( $value ) ) {

				echo '<optgroup label="' . esc_attr( $key ) . '">';

				foreach ( $value as $sub_key => $sub_value ) {

					echo '<option value="' . esc_attr( $sub_key ) . '"';

					if ( $sub_key == $id ) {
						echo ' selected';
					}

					echo '>' . esc_html( $sub_value ) . '</option>';

				}

				echo '</optgroup>';

			}

			else {

				echo '<option value="' . esc_attr( $key ) . '"';

				if ( $key == $id ) {
					echo ' selected';
				}

				echo '>' . esc_html( $value ) . '</option>';

			}

		}

		echo '</select>';

		echo '</div>';

	}


	// Container end of the form field
	public function form_field_after () {

		$path_i = $this->get_level() * 2 + 1;

		if ( !empty( $this->default ) || !empty( $this->prefix ) || !empty( $this->suffix ) ) {
			echo ' <div class="tpl-default-container">
				<i class="tpl-default-value">(';

			$text = '';

			if ( !empty( $this->prefix ) ) {
				$text .= __( 'prefix:', 'themple' ) . ' ' . tpl_kses( $this->prefix ) . '; ';
			}

			if ( !empty( $this->suffix ) ) {
				$text .= __( 'suffix:', 'themple' ) . ' ' . tpl_kses( $this->suffix ) . '; ';
			}

			if ( !empty( $this->default ) ) {
				$text .= __( 'default:', 'themple' ) . ' ' . tpl_kses( $this->format_option( $this->default, array( "key" => false ) ) ) . '; ';
			}

			echo rtrim( $text, '; ' );

			echo ')</i>
			</div>';
		}

		echo '</div>';		// .tpl-field-inner

		if ( $this->repeat !== false ) {
			if ( !isset( $this->repeat["number"] ) ) {
				echo '<div class="tpl-admin-icon tpl-remover"><span class="tpl-hovermsg"><span class="tpl-hovermsg-inner">' . __( 'Remove row', 'themple' ) . '</span></span></div>';
			}
			$this->path[$path_i]++;
		}

		echo '</div>';

	}


	// Formats the option into value
	public function format_option ( $id, $args = array() ) {

		// Deciding to return the key or the value
		if ( !isset( $args["key"] ) ) {
			$key = $this->key;
		}
		else {
			$key = $args["key"];
		}

		$values = $this->values;

		foreach ( $values as $k => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $sub_key => $sub_value ) {
					$values[$sub_key] = $sub_value;
				}
				unset( $values[$k] );
			}
		}

		if ( $key ) {
			return $id;
		}
		elseif ( !isset( $values[$id] ) ) {
			return $id;
		}
		else {
			return $this->prefix . $values[$id] . $this->suffix;
		}

	}


}
