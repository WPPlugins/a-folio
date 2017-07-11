<?php
// Setting up the options for the a-folio plugin



// Adding the Settings > a-folio Settings page to WP-Admin
add_filter( 'tpl_settings_pages', 'a_folio_settings_page', 10, 1 );

function a_folio_settings_page( $pages ) {

	$pages["a_folio_settings"] = array(
		"page_title"	=> __( 'a-folio Settings', 'a-folio' ),
		"menu_title"	=> __( 'a-folio Settings', 'a-folio' ),
		"capability"	=> 'edit_theme_options',
		"menu_slug"		=> 'a_folio_settings',
		"function"		=> 'a_folio_settings',
		"post_type"		=> 'a_folio_settings',
		"menu_func"		=> 'add_options_page'
	);
	return $pages;

}



// Add plugin-specific sections and options
add_action( 'init', 'a_folio_setup', 30 );

function a_folio_setup() {

	global $tpl_load_version;

	// Dont'd do anything if our version of Themple Framework is lower than the required
	if ( version_compare( $tpl_load_version["version"], A_FOLIO_REQ_TPL_VERSION ) < 0 ) {
		return;
	}


	add_filter( 'tpl_before_register_option', 'a_folio_disable_less_variables' );


	// First set up the required sections

	// Section for the a-folio loop settings
	$section = array (
		"name"			=> 'a_folio_loop',
		"tab"			=> __( 'Loop', 'a-folio' ),
		"title"			=> __( 'Loop / Stream settings', 'a-folio' ),
		"description"	=> __( 'Layout settings for the portfolio loop', 'a-folio' ),
		"post_type"		=> 'a_folio_settings',
	);
	tpl_register_section ( $section );



	// Section for the a-folio content settings
	$section = array (
		"name"			=> 'a_folio_content',
		"tab"			=> __( 'Content', 'a-folio' ),
		"title"			=> __( 'Content inside tiles', 'a-folio' ),
		"description"	=> __( 'Basic settings for some content elements in the tiles', 'a-folio' ),
		"post_type"		=> 'a_folio_settings',
	);
	tpl_register_section ( $section );



	// Section for the a-folio responsive settings
	$section = array (
		"name"			=> 'a_folio_responsive',
		"tab"			=> __( 'Responsive', 'a-folio' ),
		"title"			=> __( 'Responsiveness settings', 'a-folio' ),
		"description"	=> __( 'Settings for mobile / tablet screens', 'a-folio' ),
		"post_type"		=> 'a_folio_settings',
	);
	tpl_register_section ( $section );



	// Section for the a-folio system settings
	$section = array (
		"name"			=> 'a_folio_system',
		"tab"			=> __( 'System', 'a-folio' ),
		"title"			=> __( 'System settings', 'a-folio' ),
		"description"	=> __( 'Settings about how the plugin should work under the hood', 'a-folio' ),
		"post_type"		=> 'a_folio_settings',
	);
	tpl_register_section ( $section );



	// Section for the Portfolio item editor screen
	$section = array (
		"name"			=> 'a_folio_item_settings',
		"title"			=> __( 'Portfolio item settings', 'a-folio' ),
		"description"	=> __( 'Some item-specific settings', 'a-folio' ),
		"post_type"		=> 'a-folio',
	);
	tpl_register_section ( $section );



	// And now the options inside the sections



	/*

	GLOBAL PLUGIN SETTINGS

	*/


	/* LOOP */

	// Tiles layout pattern
	$tpl_option_array = array (
		"name"			=> 'a_folio_row_pattern',
		"title"			=> __( 'Tile rows pattern', 'a-folio' ),
		"description"	=> __( 'How should the tiles be arranged inside the consecutive rows? If you add more lines here, the different row patterns will follow each other in the front end.', 'a-folio' ),
		"section"		=> 'a_folio_loop',
		"type"			=> 'select',
		"values"		=> array(
			"l2"			=> __( '2 tiles / line', 'a-folio' ),
			"l3"			=> __( '3 tiles / line', 'a-folio' ),
			"l4"			=> __( '4 tiles / line', 'a-folio' ),
			"s14"			=> __( '1 big + 4 small', 'a-folio' ),
			"s41"			=> __( '4 small + 1 big', 'a-folio' ),
		),
		"repeat"		=> true,
		"admin_class"	=> 'a-folio-row-pattern',
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: default orderby settings
	$tpl_option_array = array (
		"name"			=> 'a_folio_default_orderby',
		"title"			=> __( 'Order tiles by', 'a-folio' ),
		"description"	=> __( 'This is the default setting for how to order the tiles in the front end. This setting can be overwritten inside the shortcode.', 'a-folio' ),
		"section"		=> 'a_folio_loop',
		"type"			=> 'select',
		"values"		=> array(
			"none"			=> __( 'No order', 'a-folio' ),
			"ID"			=> __( 'Post ID', 'a-folio' ),
			"title"			=> __( 'Item title', 'a-folio' ),
			"name"			=> __( 'Item slug', 'a-folio' ),
			"date"			=> __( 'Date created', 'a-folio' ),
			"modified"		=> __( 'Last modified', 'a-folio' ),
			"rand"			=> __( 'Random', 'a-folio' ),
			"menu_order"	=> __( 'Menu order', 'a-folio' ),
			"post__in"		=> __( 'Post IDs in shortcode', 'a-folio' ),
		),
		"key"			=> true,
		"default"		=> "date",
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: default order (ASC/DESC) settings
	$tpl_option_array = array (
		"name"			=> 'a_folio_default_order',
		"title"			=> __( 'Order direction', 'a-folio' ),
		"description"	=> __( 'Should the tiles follow each other in ascending or descending order? This setting can be overwritten inside the shortcode.', 'a-folio' ),
		"section"		=> 'a_folio_loop',
		"type"			=> 'select',
		"values"		=> array(
			"ASC"			=> __( 'Ascending', 'a-folio' ),
			"DESC"			=> __( 'Descending', 'a-folio' ),
		),
		"key"			=> true,
		"default"		=> "DESC",
	);
	tpl_register_option ( $tpl_option_array );



	// Use the CTA tile or not
	$tpl_option_array = array (
		"name"			=> 'a_folio_cta',
		"title"			=> __( 'Use the last tile as CTA?', 'a-folio' ),
		"description"	=> __( 'If Yes, the last tile will be a special Call to Action tile. Else it\'s a normal tile.', 'a-folio' ),
		"section"		=> 'a_folio_loop',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'no',
	);
	tpl_register_option ( $tpl_option_array );



	// CTA tile settings
	$tpl_option_array = array (
		"name"			=> 'a_folio_cta_settings',
		"title"			=> __( 'Call to Action tile settings', 'a-folio' ),
		"description"	=> __( 'Settings for the Call to Action (CTA) tile.', 'a-folio' ),
		"section"		=> 'a_folio_loop',
		"type"			=> 'combined',
		"parts"			=> array(
			array(
				"name"			=> 'a_folio_cta_text',
				"title"			=> __( 'Call to Action text', 'a-folio' ),
				"description"	=> __( 'The text on the Call to Action tile.', 'a-folio' ),
				"type"			=> 'textarea',
				"placeholder"	=> __( 'Write something that catches attention.', 'a-folio' ),
			),
			array(
				"name"			=> 'a_folio_cta_url',
				"title"			=> __( 'Call to Action URL', 'a-folio' ),
				"description"	=> __( 'The URL where the Call to Action tile is linked to.', 'a-folio' ),
				"type"			=> 'text',
				"placeholder"	=> __( 'URL starting with http://, https://, mailto:, etc.', 'a-folio' ),
			),
			array(
				"name"			=> 'a_folio_cta_newtab',
				"title"			=> __( 'New browser tab', 'a-folio' ),
				"description"	=> __( 'Open the CTA link in a new browser tab?', 'a-folio' ),
				"type"			=> 'select',
				"values"		=> array(
					"yes"			=> __( 'Yes', 'a-folio' ),
					"no"			=> __( 'No', 'a-folio' ),
				),
				"default"		=> 'no',
				"condition"		=> array(
					array(
						"type"		=> 'option',
						"name"		=> '_THIS_/a_folio_cta_url',
						"relation"	=> '!=',
						"value"		=> '',
					)
				),
			),
			array(
				"name"			=> 'a_folio_cta_bgcolor',
				"title"			=> __( 'Background color of the CTA tile', 'a-folio' ),
				"description"	=> __( 'Select a color for the background.', 'a-folio' ),
				"type"			=> 'color',
				"default"		=> '#aaaaaa',
			),
		),
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_folio_cta',
				"relation"	=> '=',
				"value"		=> 'yes',
			)
		),
	);
	tpl_register_option ( $tpl_option_array );



	/* CONTENT */

	// Select field: Item subtitles
	$tpl_option_array = array (
		"name"			=> 'a_folio_subtitles',
		"title"			=> __( 'Item subtitles', 'a-folio' ),
		"description"	=> __( 'What should be displayed in the big boxes as the item subtitle? If Short text is selected, an input field will appear for it in the Portfolio item editor.', 'a-folio' ),
		"section"		=> 'a_folio_content',
		"type"			=> 'select',
		"values"		=> array(
			"no"			=> __( 'Nothing', 'a-folio' ),
			"text"			=> __( 'Short text', 'a-folio' ),
			"cat"			=> __( 'List of item categories', 'a-folio' ),
		),
		"key"			=> true,
		"default"		=> "text",
	);
	tpl_register_option ( $tpl_option_array );



	// Where should the Read more button link?
	$tpl_option_array = array (
		"name"			=> 'a_folio_button_link',
		"title"			=> __( 'Display button link', 'a-folio' ),
		"description"	=> __( 'If yes, a button link is displayed at the bottom of the tile covers. Its look generally depends on the theme.', 'a-folio' ),
		"section"		=> 'a_folio_content',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'yes',
	);
	tpl_register_option ( $tpl_option_array );



	// The read more text displayed on the button line of the cover layer
	$tpl_option_array = array (
		"name"			=> 'a_folio_readmore',
		"title"			=> __( 'Read more text', 'a-folio' ),
		"description"	=> __( 'The read more text displayed on the button line of the cover layer.', 'a-folio' ),
		"section"		=> 'a_folio_content',
		"type"			=> 'text',
		"default"		=> __( 'Read more', 'a-folio' ),
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_folio_button_link',
				"relation"	=> '!=',
				"value"		=> 'no',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Optional extra button class
	$tpl_option_array = array (
		"name"			=> 'a_folio_button_class',
		"title"			=> __( 'Default button class', 'a-folio' ),
		"description"	=> __( 'You can add here a CSS class that will be attached to the buttons. You can override this setting in the shortcode.', 'a-folio' ),
		"section"		=> 'a_folio_content',
		"type"			=> 'text',
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_folio_button_link',
				"relation"	=> '!=',
				"value"		=> 'no',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Should we hide the icons in the tiles?
	$tpl_option_array = array (
		"name"			=> 'a_folio_hide_icons',
		"title"			=> __( 'Hide icons', 'a-folio' ),
		"description"	=> __( 'If yes, no icons are displayed in the header of the cover.', 'a-folio' ),
		"section"		=> 'a_folio_content',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'no',
	);
	tpl_register_option ( $tpl_option_array );



	/* RESPONSIVE */

	// Select field: enable responsiveness?
	$tpl_option_array = array (
		"name"			=> 'a_folio_responsive',
		"title"			=> __( 'Responsive behavior', 'a-folio' ),
		"description"	=> __( 'Should the built-in styles support responsive behavior? "No" is only recommended for non-responsive themes.', 'a-folio' ),
		"section"		=> 'a_folio_responsive',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'yes',
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_folio_load_css',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Combined field: Responsive breakpoints
	$tpl_option_array = array (
		"name"			=> 'a_folio_responsive_breakpoints',
		"title"			=> __( 'Responsive breakpoints', 'a-folio' ),
		"description"	=> __( 'You can fine-tune here the behavior of the team member columns for smaller screens.', 'a-folio' ),
		"section"		=> 'a_folio_responsive',
		"type"			=> 'combined',
		"parts"		=> array(
			array(
				"name"			=> 'breakpoint_1',
				"title"			=> __( 'Breakpoint #1 (2,3 → 1, 4 → 2)', 'a-folio' ),
				"description"	=> __( 'Under this window width the 2- and 3-column layouts change to 1-column layout and the 4-column layout changes to 2-column layout', 'a-folio' ),
				"type"			=> 'number',
				"default"		=> 1200,
				"suffix"		=> 'px',
			),
			array(
				"name"			=> 'breakpoint_2',
				"title"			=> __( 'Breakpoint #2 (2-4 → 1)', 'a-folio' ),
				"description"	=> __( 'Under this window width all the multi-column layouts change to 1-column layout', 'a-folio' ),
				"type"			=> 'number',
				"default"		=> 480,
				"suffix"		=> 'px',
			),
		),
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_folio_load_css',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
			array(
				"type"		=> 'option',
				"name"		=> 'a_folio_responsive',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	/* SYSTEM */

	// Select field: load the front end CSS or not
	$tpl_option_array = array (
		"name"			=> 'a_folio_load_css',
		"title"			=> __( 'Load basic front end CSS?', 'a-folio' ),
		"description"	=> __( 'If Yes is selected, a small CSS file will be loaded in the front end in order to give some shape for the portfolio items.<br>
		Select No if you have your own style rules for portfolio items in your theme\'s stylesheets (recommended only for experts).', 'a-folio' ),
		"section"		=> 'a_folio_system',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'yes',
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: should we delete all data upon plugin uninstall
	$tpl_option_array = array (
		"name"			=> 'a_folio_delete_data',
		"title"			=> __( 'Delete data on plugin uninstall?', 'a-folio' ),
		"description"	=> __( 'If yes, all data will be removed when you delete the a-folio plugin. Otherwise the data added by the plugin will be kept in the database.', 'a-folio' ),
		"section"		=> 'a_folio_system',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'yes',
	);
	tpl_register_option ( $tpl_option_array );





	/*

	LOCAL PORTFOLIO ITEM SETTINGS

	*/


	// Add a subtitle field to the item editor
	$tpl_option_array = array (
		"name"			=> 'a_folio_item_subtitle',
		"title"			=> __( 'Subtitle', 'a-folio' ),
		"description"	=> __( 'This is displayed under the title when Short text is selected as the subtitle on the plugin settings page.', 'a-folio' ),
		"section"		=> 'a_folio_item_settings',
		"type"			=> 'text',
		"placeholder"	=> __( 'Add your subtitle here...', 'a-folio' ),
	);
	tpl_register_option ( $tpl_option_array );



	// Font awesome icon that goes into the box
	$tpl_option_array = array (
		"name"			=> 'a_folio_item_icon',
		"title"			=> __( 'Icon', 'a-folio' ),
		"description"	=> __( 'The icon which is displayed in the item\'s box.', 'a-folio' ),
		"section"		=> 'a_folio_item_settings',
		"type"			=> 'font_awesome',
		"default"		=> 'globe',
	);
	tpl_register_option ( $tpl_option_array );



	// Custom URL for the button
	$tpl_option_array = array (
		"name"			=> 'a_folio_item_button_url',
		"title"			=> __( 'Custom link URL', 'a-folio' ),
		"description"	=> __( 'If specified, the button link at the bottom of the tile cover will link to this URL. If left empty, it will link to the single item page.', 'a-folio' ),
		"section"		=> 'a_folio_item_settings',
		"type"			=> 'text',
		"placeholder"	=> __( 'URL starting with http:// or https://', 'a-folio' ),
	);
	tpl_register_option ( $tpl_option_array );



	// Should the button link open in new browser tab?
	$tpl_option_array = array (
		"name"			=> 'a_folio_item_button_newtab',
		"title"			=> __( 'Link opens in new tab', 'a-folio' ),
		"description"	=> __( 'If yes, the link will open in new browser tab.', 'a-folio' ),
		"section"		=> 'a_folio_item_settings',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-folio' ),
			"no"			=> __( 'No', 'a-folio' ),
		),
		"default"		=> 'no',
	);
	tpl_register_option ( $tpl_option_array );




	/*

	OTHER

	*/


	// If it's not the theme version of Themple, enqueue the Font Awesome library
	if ( !defined( 'THEMPLE_THEME' ) ) {
		add_action( 'wp_enqueue_scripts', 'a_folio_fa_css' );
	}


	remove_filter( 'tpl_before_register_option', 'a_folio_disable_less_variables' );

}



// With this function we disable the plugin's options to be parsed into a Themple-based theme's LESS variables. This way we eliminate possible fatal errors.
function a_folio_disable_less_variables( $narr = array() ) {
	$narr["less"] = false;
	return $narr;
}
