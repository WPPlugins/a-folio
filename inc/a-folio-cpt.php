<?php
// Custom Post Type(s) added by the a-folio plugin



// Register Custom Post Type: Portfolio item (a-folio)
add_action( 'init', 'a_folio_cpt', 0 );

function a_folio_cpt() {

	$labels = array(
		'name'                  => _x( 'Portfolio items', 'Post Type General Name', 'a-folio' ),
		'singular_name'         => _x( 'Portfolio item', 'Post Type Singular Name', 'a-folio' ),
		'menu_name'             => __( 'Portfolio items', 'a-folio' ),
		'name_admin_bar'        => __( 'Portfolio item', 'a-folio' ),
		'archives'              => __( 'Portfolio Archives', 'a-folio' ),
		'parent_item_colon'     => __( 'Parent item:', 'a-folio' ),
		'all_items'             => __( 'All items', 'a-folio' ),
		'add_new_item'          => __( 'Add New item', 'a-folio' ),
		'add_new'               => __( 'Add New', 'a-folio' ),
		'new_item'              => __( 'New item', 'a-folio' ),
		'edit_item'             => __( 'Edit item', 'a-folio' ),
		'update_item'           => __( 'Update item', 'a-folio' ),
		'view_item'             => __( 'View item', 'a-folio' ),
		'search_items'          => __( 'Search item', 'a-folio' ),
		'not_found'             => __( 'Not found', 'a-folio' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'a-folio' ),
		'featured_image'        => __( 'Cover image', 'a-folio' ),
		'set_featured_image'    => __( 'Set cover image', 'a-folio' ),
		'remove_featured_image' => __( 'Remove cover image', 'a-folio' ),
		'use_featured_image'    => __( 'Use as cover image', 'a-folio' ),
		'insert_into_item'      => __( 'Insert into item\'s page', 'a-folio' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item\'s page', 'a-folio' ),
		'items_list'            => __( 'Items list', 'a-folio' ),
		'items_list_navigation' => __( 'Items list navigation', 'a-folio' ),
		'filter_items_list'     => __( 'Filter items list', 'a-folio' ),
	);
	$args = array(
		'label'                 => __( 'Portfolio item', 'a-folio' ),
		'description'           => __( 'Portfolio of your projects', 'a-folio' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 25,
		'menu_icon'             => 'dashicons-portfolio',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'				=> array( 'slug' => 'portfolio' ),
	);
	register_post_type( 'a-folio', $args );

}



// Register Custom Taxonomy: Portfolio Categories
function a_folio_categories() {

	$labels = array(
		'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', 'a-folio' ),
		'singular_name'              => _x( 'Portfolio Category', 'Taxonomy Singular Name', 'a-folio' ),
		'menu_name'                  => __( 'Portfolio Categories', 'a-folio' ),
		'all_items'                  => __( 'All Categories', 'a-folio' ),
		'parent_item'                => __( 'Parent Category', 'a-folio' ),
		'parent_item_colon'          => __( 'Parent Category:', 'a-folio' ),
		'new_item_name'              => __( 'New Portfolio Category', 'a-folio' ),
		'add_new_item'               => __( 'Add New Portfolio Category', 'a-folio' ),
		'edit_item'                  => __( 'Edit Portfolio Category', 'a-folio' ),
		'update_item'                => __( 'Update Portfolio Category', 'a-folio' ),
		'view_item'                  => __( 'View Portfolio Category', 'a-folio' ),
		'separate_items_with_commas' => __( 'You can add more Categories here.', 'a-folio' ),
		'add_or_remove_items'        => __( 'Add or remove Portfolio Categories', 'a-folio' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'a-folio' ),
		'popular_items'              => __( 'Popular Categories', 'a-folio' ),
		'search_items'               => __( 'Search Portfolio Categories', 'a-folio' ),
		'not_found'                  => __( 'Not Found', 'a-folio' ),
		'no_terms'                   => __( 'No items', 'a-folio' ),
		'items_list'                 => __( 'Categories list', 'a-folio' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'a-folio' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'a-folio-category', array( 'a-folio' ), $args );

}
add_action( 'init', 'a_folio_categories', 0 );
