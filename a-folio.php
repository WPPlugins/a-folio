<?php
/*
Plugin Name: a-folio
Plugin URI:  https://a-idea.studio/a-folio/
Description: Manage and display your portfolio projects using a customizable grid layout
Version:     1.0
Author:      a-idea studio
Author URI:  https://a-idea.studio/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: a-folio
Domain Path: /languages
*/


// Defining the current plugin version
define( 'A_FOLIO_VERSION', '1.0' );


// Including the plugin files
require_once "inc/a-folio-cpt.php";
require_once "inc/a-folio-init.php";
require_once "inc/a-folio-functions.php";
require_once "inc/a-folio-options.php";
require_once "inc/a-folio-shortcode.php";
require_once "inc/a-folio-pb-app.php";


// Rewrite rules for the CPT added by this plugin
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'a_folio_flush_rewrites' );


// Load the textdomain for i18n
add_action( 'plugins_loaded', function() {
	if ( is_admin() ) {
		load_plugin_textdomain( 'a-folio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
} );
