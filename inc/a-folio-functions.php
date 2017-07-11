<?php
// Additional functions used by the a-folio plugin


// Add the subtitle and the category on the single posts of the a-folio CPT
function a_folio_filter_the_content( $content ) {

	if ( get_post_type() == 'a-folio' ) {

		$custom_content = '';

	 	if ( tpl_get_option( 'a_folio_item_subtitle' ) != '' ) {
			$custom_content .= '<h2 class="a-folio-single-subtitle">' . tpl_get_value( 'a_folio_item_subtitle' ) . '</h2>';
		}

		if ( wp_get_post_terms( get_the_ID(), 'a-folio-category' ) ) {
			$custom_content .= '<p class="a_folio_single_categories"><span>' . __( 'Categories:', 'a-folio' ) . ' </span>' . a_folio_category_list() . '</p>';
		}

		$content = $custom_content . $content;

	}

    return $content;

}
add_filter( 'the_content', 'a_folio_filter_the_content' );



// Creates a comma-separated list of portfolio categories - TO BE USED IN THE LOOP
function a_folio_category_list() {

	$output = '';

	$categories = wp_get_post_terms( get_the_ID(), 'a-folio-category' );
	$i = 0;
	foreach ( $categories as $category ) {
		if ( $i > 0 ) {
			$output .=', ';
		}
		$output .= $category->name;
		$i++;
	}

	return $output;

}



// Puts together the button link for the tiles - TO BE USED IN THE LOOP
function a_folio_get_button_link( $extra_class = '' ) {

	$output = '';

	$output .= '<a class="' . $extra_class . '" href="';

	if ( tpl_get_option( 'a_folio_item_button_url' ) == '' ) {
		$output .= get_permalink();
	}
	else {
		$output .= tpl_get_value( 'a_folio_item_button_url' );
	}

	$output .= '"';

	if ( tpl_get_option( 'a_folio_item_button_newtab' ) == 'yes' ) {
		$output .= ' target="_blank"';
	}

	$output .= '>' . tpl_get_value( 'a_folio_readmore' ) . '</a>';

	return $output;

}
