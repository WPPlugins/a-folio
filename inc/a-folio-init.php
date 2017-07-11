<?php
// Initial settings for the a-folio plugin



// The minimum required Themple Framework version to use with this plugin. It's needed to ensure the plugin is compatible with the theme
define( 'A_FOLIO_REQ_TPL_VERSION', '1.2' );



// Themple Lite Purgatory
global $tpl_load_version;
$a_folio_tpl_version = array(
	"type"		=> 'plugin',
	"name"		=> 'a-folio',
	"version"	=> '1.2',
);
if ( !is_array( $tpl_load_version ) ) {
	$tpl_load_version = $a_folio_tpl_version;
}
else {
	if ( version_compare( $tpl_load_version["version"], $a_folio_tpl_version["version"] ) < 0 ) {
		$tpl_load_version = $a_folio_tpl_version;
	}
}



// Detect if there is a built-in Themple version in the theme. If yes, we'll use that version. If no, we'll use the plugin's own version. In the different cases it needs to be loaded with different hooks.
if ( get_option( 'tpl_version' ) !== false || defined( 'THEMPLE_THEME' ) ) {
	add_action( 'init', 'a_folio_init', 11 );
}
else {
	add_action( 'after_setup_theme', 'a_folio_init' );
}

// The initializer function
function a_folio_init() {

	global $tpl_load_version, $a_folio_tpl_version;

	// Check if the theme contains a version of Themple Framework and connect to it if the version numbers are OK
	if ( get_option( 'tpl_version' ) !== false || defined( 'THEMPLE_THEME' ) ) {

		if ( defined( 'THEMPLE_VERSION' ) ) {
			$tpl_version = THEMPLE_VERSION;
		}
		else {
			$tpl_version = get_option( 'tpl_version' );
		}

		$tpl_load_version = array(
			"type"		=> 'theme',
			"name"		=> get_stylesheet(),
			"version"	=> $tpl_version,
		);

		// Show an error message if the theme's Themple version is too old
		if ( version_compare( $tpl_version, A_FOLIO_REQ_TPL_VERSION ) < 0 ) {
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-error"><p><b>a-folio:</b> ' . sprintf( __( 'It looks like the version of Themple Framework (%1$s) you are using in your current theme is older than the framework version required by the a-folio plugin (%2$s). Please update your theme to the latest version or contact your web developer.', 'a-folio' ), $tpl_version, A_FOLIO_REQ_TPL_VERSION ) . '</p></div>';
			} );
		}

		require_once get_template_directory() . "/framework/themple.php";

	}

	// If we use a non-Themple-based theme, go with the plugin's built-in Themple Lite version
	else if ( $tpl_load_version["type"] == 'plugin' && $tpl_load_version["name"] == $a_folio_tpl_version["name"] ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "framework/themple.php";

		// Load the framework's l10n files in this case
		$mo_filename = plugin_dir_path( dirname( __FILE__ ) ) . 'framework/languages/' . get_locale() . '.mo';
		if ( is_admin() && file_exists( $mo_filename ) ) {
			load_textdomain( 'themple', $mo_filename );
		}

	}

}



// This function is needed for interpreting the Settings page settings.
function a_folio_settings () {

	tpl_settings_page( 'a_folio_settings');

}



/* SCRIPT HANDLING */

// Font Awesome CSS loader function. Loads the FA CSS file if it's not yet available in the front end
function a_folio_fa_css() {

	wp_enqueue_style( 'font-awesome', plugins_url( 'assets/font-awesome.min.css', dirname( __FILE__ ) ) );

}



// Adding some extra settings just to make sure JS works fine
add_filter( 'tpl_admin_js_strings', 'a_folio_admin_js_values', 10, 1 );

function a_folio_admin_js_values( $values ) {

	$values["remover_confirm"] = 'yes';
	$values["pb_fewer_confirm"] = 'yes';
	$values["pb_fewer_instances"] = '';

	return $values;
}



// Load the plugin's front end CSS if it's enabled in admin
add_action( 'wp_enqueue_scripts', function() {

	if ( tpl_get_option( 'a_folio_load_css' ) == 'yes' ) {

		wp_register_style( 'a-folio-style', plugins_url( 'assets/a-folio.min.css', dirname( __FILE__ ) ), array(), A_FOLIO_VERSION );
		wp_register_script( 'a-folio-script', plugins_url( 'assets/a-folio.min.js', dirname( __FILE__ ) ), array( 'jquery' ), A_FOLIO_VERSION );

		// Add some responsive code if it was enabled in plugin settings
		if ( tpl_get_option( 'a_folio_responsive' ) == 'yes' ) {

			$custom_css = '@media (max-width: ' . tpl_get_value( 'a_folio_responsive_breakpoints/0/breakpoint_1' ) . ') {
				.a-folio-tile-size-1_2, .a-folio-tiled-container { width: 100%; }
				.a-folio-tile-size-1_4 { width: 50%; }
				.a-folio-tile-size-1_3 { width: 100%; }
			}
			@media (max-width: ' . tpl_get_value( 'a_folio_responsive_breakpoints/0/breakpoint_2' ) . ') {
				.a-folio-tile-size-1_4 { width: 100%; }
				.a-folio-tiled-container { height: auto; padding-bottom: 0; }
				.a-folio-tiled-container .a-folio-tile-size-1_4 { padding-left: 0; padding-right: 0; width: 100%; }
			}';
			wp_add_inline_style( 'a-folio-style', esc_html( $custom_css ) );

		}

	}

} );



// JS functions for admin panel
add_action( 'admin_enqueue_scripts', function() {

	wp_enqueue_script( 'a-folio-admin-script', plugins_url( '', dirname( __FILE__ ) ) . '/assets/a-folio-admin.min.js', array( 'jquery', 'tpl-admin-scripts' ) );
	wp_enqueue_style( 'a-folio-admin-style', plugins_url( '', dirname( __FILE__ ) ) . '/assets/a-folio-admin.min.css' );

});

/* END OF SCRIPT HANDLING */



// Add the default image size for the plugin
add_filter( 'tpl_image_sizes', 'a_folio_image_sizes', 10, 1 );

function a_folio_image_sizes( $image_sizes = array() ) {

	// The large tile image size
	$image_sizes["a-folio-tile"] = array(
		'title'		=> __( 'a-folio tile', 'a-folio' ),
		'width'		=> 580,
		'height'	=> 440,
		'crop'		=> array( 'center', 'center' ),
		'select'	=> true,
	);

	return $image_sizes;

}



// Rewrite rules update to avoid 404 errors
function a_folio_flush_rewrites() {

	a_folio_cpt();
	flush_rewrite_rules();

}
