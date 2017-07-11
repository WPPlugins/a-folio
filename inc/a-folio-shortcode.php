<?php
// Shortcodes added by the a-folio plugin



// Add Shortcode: [a-folio]
function a_folio_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'class'			=> '',
			'button_class'	=> '',
			'num'			=> '-1',
			'pattern_num'	=> '',
			'ids'			=> '',
			'exclude'		=> '',
			'order'			=> tpl_get_option( 'a_folio_default_order' ),
			'orderby'		=> tpl_get_option( 'a_folio_default_orderby' ),
			'category'		=> '',
			'pattern'		=> '',
			'disable_cta'	=> false,
		),
		$atts
	);

	// Add extra classes to the container if needed
	if ( $atts["class"] != '' ) {
		$extra_class = ' ' . $atts["class"];
	}
	else {
		$extra_class = '';
	}

	// Set up the optional extra class for tile buttons
	if ( tpl_get_option( 'a_folio_button_class' ) != '' && $atts["button_class"] == '' ) {
		$atts["button_class"] = tpl_get_option( 'a_folio_button_class' );
	}

	// Initialize the output
	$output = '<div class="a-folio' . esc_attr( $extra_class ) . '">';

	// Now run a loop with the system's folio Members
	$args = array (
		'post_type'				=> array( 'a-folio' ),
		'posts_per_page'		=> $atts["num"],
		'ignore_sticky_posts'	=> true,
		'order'					=> $atts["order"],
		'orderby'				=> $atts["orderby"],
		'status'				=> 'publish',
	);

	// Display only the posts with specific IDs if the ids="" parameter is not empty
	if ( $atts["ids"] != '' ) {
		$args["post__in"] = explode( ',', $atts["ids"] );
	}

	// Or exclude some posts in the other case
	if ( $atts["exclude"] != '' ) {
		$args["post__not_in"] = explode( ',', $atts["exclude"] );
	}

	// Or exclude some posts in the other case
	if ( $atts["category"] != '' ) {
		$args["tax_query"] = array(
			array(
				'taxonomy'	=> 'a-folio-category',
				'field'		=> 'slug',
				'terms'		=> explode( ',', $atts["category"] ),
			),
		);
	}

	// Read the layout pattern
	if ( $atts["pattern"] == '' ) {
		$pattern = tpl_get_option( 'a_folio_row_pattern' );
	}
	else {
		$pattern = explode( '-', $atts["pattern"] );
	}
	$pattern_array = array();

	if ( $pattern == '' ) {
		$pattern = array(
			0	=> 'l2',
		);
	}

	// Set up the pattern array that will eventually draw the tiles
	foreach ( $pattern as $p ) {

		// If all the items in the line are equal size
		if ( $p[0] == 'l' ) {

			for ( $i = 0; $i < $p[1]; $i++ ) {
				if ( $p[1] <= 2 ) {
					$size = 'large';
				}
				elseif ( $p[1] == 3 ) {
					$size = 'medium';
				}
				else {
					$size = 'small';
				}

				$pattern_array[] = array(
					"num"	=> $p[1],
					"size"	=> $size,
					"tiled"	=> false,
				);
			}

		}

		// If it's a big + small tiles layout
		if ( $p[0] == 's' ) {

			for ( $i = 1; $i < strlen( $p ); $i++ ) {
				if ( $p[$i] == '1' ) {
					$pattern_array[] = array(
						"num"	=> '2',
						"size" => 'large',
						"tiled"	=> false,
					);
				}
				if ( $p[$i] == '4' ) {
					for ( $j = 0; $j < $p[$i]; $j++ ) {
						$pattern_array[] = array(
							"num"	=> '4',
							"size"	=> 'small',
							"tiled"	=> true,
						);
					}
				}
			}

		}

	}

	// If the pattern instances setting was used in the shortcode, use it instead of the "num" setting
	if ( tpl_get_option( 'a_folio_cta' ) == 'yes' ) {
		if ( $atts["disable_cta"] == true ) {
			$disable_cta = true;
		}
		else {
			$disable_cta = false;
		}
	}
	else {
		$disable_cta = true;
	}


	if ( $atts["pattern_num"] != '' ) {

		$args["posts_per_page"] = $atts["pattern_num"] * count( $pattern_array );

		// Decrease the number with 1 if we use the CTA tile
		if ( !$disable_cta ) {
			$args["posts_per_page"]--;
		}

	}

	// Setting up filters to customize the excerpts displayed in the tiles
	add_filter( 'excerpt_more', 'a_folio_excerpt_more' );
	add_filter( 'excerpt_length', 'a_folio_excerpt_length' );
	remove_filter( 'the_content', 'a_folio_filter_the_content' );

	// Do the query
	$query = new WP_Query( $args );

	// Loop indicators that helps assigning the classes
	$li = 0;
	$tiled_open = false;

	// The Loop
	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {
			$query->the_post();

			// Get the background image
			$thumb_id = get_post_thumbnail_id();
			if ( $thumb_id != '' ) {
				$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'a-folio-tile', true );
				$thumb_url = $thumb_url_array[0];
			}
			else {
				$thumb_url = '';
			}

			// The "tiled" layout means that there are more lines of small tiles next to a large tile
			if ( $pattern_array[$li]["tiled"] == true && !$tiled_open ) {
				$output .= '<div class="a-folio-tiled-container a-folio-tile-wrapper">';
				$tiled_count = 1;
				$tiled_open = true;
			}

			// Now draw the inner part of the tile
			$output .= '<div id="a_folio_' . get_the_ID() . '" class="a-folio-tile-wrapper a-folio-tile-size-1_' . $pattern_array[$li]["num"];

			// Add the size class
			if ( $pattern_array[$li]["size"] != 'large' ) {
				$output .= ' a-folio-small-title';
			}

			// Add category classes
			if ( wp_get_post_terms( get_the_ID(), 'a-folio-category' ) ) {
				$categories = wp_get_post_terms( get_the_ID(), 'a-folio-category' );
				foreach ( $categories as $cat ) {
					$output .= ' a-folio-cat-' . $cat->slug;
				}
			}

			// Add a special class if there is no icon on the tile
			if ( tpl_get_option( 'a_folio_hide_icons' ) == 'yes' ) {
				$output .= ' a-folio-noicon';
			}

			// Continue with the inner tile
			$output .= '">
					<div class="a-folio-tile" style="background-image: url(' . $thumb_url . ');">
						<div class="a-folio-tile-cover">
							<div class="a-folio-tile-header">';

			if ( tpl_get_option( 'a_folio_hide_icons' ) != 'yes' ) {
				$output .= '<div class="a-folio-tile-icon">' . tpl_get_value( 'a_folio_item_icon' ) . '</div>';
			}

			$output .= '		<div class="a-folio-tile-title">
									<div class="a-folio-tile-title-inner">
										<h1>' . get_the_title() . '</h1>';

			if ( $pattern_array[$li]["size"] == 'large' ) {
				if ( tpl_get_option( 'a_folio_subtitles' ) == 'text' ) {
					$subtitle = tpl_get_value( 'a_folio_item_subtitle' );
				}
				if ( tpl_get_option( 'a_folio_subtitles' ) == 'cat' ) {
					$subtitle = a_folio_category_list();
				}
				if ( $subtitle != '' ) {
					$output .= '<h2>' . $subtitle . '</h2>';
				}
			}

			$output .= '			</div>
								</div>
							</div>
							<div class="a-folio-tile-content">';

			if ( $pattern_array[$li]["size"] != 'small' ) {
				$output .= '	<div class="a-folio-tile-desc">' . get_the_excerpt() . '</div>';
			}

			if ( tpl_get_option( 'a_folio_button_link' ) != 'no' ) {
				$output	.= '		<div class="a-folio-tile-button">' . a_folio_get_button_link( $atts["button_class"] ) . '</div>';
			}

			$output .= '	</div>
						</div>
					</div>
				</div>';

			// Close down the "tiled" layout if reached the max number of small tiles
			if ( $pattern_array[$li]["tiled"] == true ) {
				$tiled_count++;
				if ( $tiled_count > 4 ) {
					$output .= '
			</div>';
					$tiled_open = false;
				}
			}

			$li++;

			// Restart the pattern if we reached its end
			if ( $li > count( $pattern_array ) - 1 ) {
				$li = 0;
			}

		}

	}

	else {
		// no posts found
	}

	// Add the CTA tile if selected
	if ( !$disable_cta ) {

		if ( $pattern_array[$li]["tiled"] == true && !$tiled_open ) {
			$output .= '<div class="a-folio-tiled-container a-folio-tile-wrapper">';
			$tiled_open = true;
		}

		$output .= '<div class="a-folio-tile-wrapper a-folio-tile-size-1_' . $pattern_array[$li]["num"] . '">
					<div class="a-folio-tile a-folio-cta" style="background-color: ' . tpl_get_value( 'a_folio_cta_settings/0/a_folio_cta_bgcolor' ) . '">';

		if ( tpl_get_option( 'a_folio_cta_settings/0/a_folio_cta_url' ) != '' ) {

			$output .= '<a class="a-folio-cta-text-outer" href="' . tpl_get_value( 'a_folio_cta_settings/0/a_folio_cta_url' ) . '"';
			if ( tpl_get_option( 'a_folio_cta_settings/0/a_folio_cta_newtab' ) == 'yes' ) {
				$output .= ' target="_blank"';
			}
			$output .= '>';

		}
		else {

			$output .= '<span class="a-folio-cta-text-outer">';

		}

		$output .= '<span class="a-folio-cta-text">' . tpl_get_value( 'a_folio_cta_settings/0/a_folio_cta_text' ) . '</span>';

		if ( tpl_get_option( 'a_folio_cta_settings/0/a_folio_cta_url' ) != '' ) {
			$output .= '</a>';
		}
		else {
			$output .= '</span>';
		}

		$output .= '</div>
				</div>';

		if ( $tiled_open ) {
			$output .= '</div>';
		}

	}

	// Restoring previously modified filters
	remove_filter( 'excerpt_more', 'a_folio_excerpt_more' );
	remove_filter( 'excerpt_length', 'a_folio_excerpt_length' );
	add_filter( 'the_content', 'a_folio_filter_the_content' );

	// Close main div
	$output .= '
		</div><!-- main a-folio div -->';

	// Restore original Post Data
	wp_reset_postdata();

	if ( tpl_get_option( 'a_folio_load_css' ) == 'yes' ) {
		wp_enqueue_style( 'a-folio-style' );
		wp_enqueue_script( 'a-folio-script' );
	}


	// And return the output
	return $output;

}

add_shortcode( 'a-folio', 'a_folio_shortcode' );



// Replaces the excerpt "Read More" text with a button
function a_folio_excerpt_more( $more ) {
    global $post;
	return '…';
}



// Change excerpt length
function a_folio_excerpt_length( $length ) {
	return 80;
}
