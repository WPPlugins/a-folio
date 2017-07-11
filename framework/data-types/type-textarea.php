<?php

// The file must have the type-[data-type].php filename format


class TPL_Textarea extends TPL_Data_Type {

	protected	$size			= 8;		// Number of rows in wp-admin
	protected	$less_string	= true;		// In case an inherited class can use LESS, it is in string format by default
	protected	$less			= false;	// LESS won't work with multi-line texts, so turning it OFF here


	// Writes the form field in wp-admin
	public function form_field_content ( $for_bank = false ) {

		if ( $for_bank == true ) {
			$value = $this->default;
		}
		else {
			$value = esc_textarea( $this->get_option() );
		}

		echo '<div class="tpl-textarea-wrapper tpl-datatype-container">';

		echo '<textarea id="' . esc_attr( $this->form_ref() ) . '" name="' . esc_attr( $this->form_ref() ) . '" rows="' . esc_attr( $this->size ) . '">'
		. $value
		. '</textarea>';

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
				$text .= __( 'default:', 'themple' ) . ' <pre>' . esc_html( $this->prefix . $this->default . $this->suffix ) . '</pre>; ';
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
	public function format_option ( $value, $args = array() ) {

		return nl2br( $this->prefix . $value . $this->suffix );

	}


	// Echoes the value of the option
	public function value ( $args = array() ) {

		if ( $this->repeat !== false ) {

			$values = $this->get_value( $args );

			if ( is_array( $values ) ) {
				echo '<ul>';
				foreach ( $values as $value ) {
					echo '<li>' . tpl_kses( $value ) . '</li>';
				}
				echo '</ul>';
				return;
			}

		}

		echo '<p>' . tpl_kses( $this->get_value( $args ) ) . '</p>';

	}



}
