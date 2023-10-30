<?php

// Establishment CPT
function establishment_cpt_init() {
	$establishment_labels = array(
		'name' => 'Establishments',
		'singular_name' => 'Establishment',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Establishment',
		'edit_item' => 'Edit Establishment',
		'new_item' => 'New Establishment',
		'all_items' => 'All Establishments',
		'view_item' => 'View Establishment',
		'search_items' => 'Search Establishments',
		'not_found' => 'No establishments found',
		'not_found_in_trash' => 'No establishments found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Establishments'
	);
	$establishment_args = array(
		'labels' => $establishment_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'query_var' => true,
		'exclude_from_search' => true,
		'rewrite' => true,
		'capabilities' => array(
			'publish_posts' => 'delete_others_posts',
			'edit_posts' => 'delete_others_posts',
			'edit_others_posts' => 'delete_others_posts',
			'delete_posts' => 'delete_others_posts',
			'delete_others_posts' => 'delete_others_posts',
			'read_private_posts' => 'delete_others_posts',
			'edit_post' => 'delete_others_posts',
			'delete_post' => 'delete_others_posts',
			'read_post' => 'delete_others_posts'
		),
		'has_archive' => 'document',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'thumbnail' )
	);
	register_post_type( 'establishment', $establishment_args );
}

add_action( 'init', 'establishment_cpt_init' );

function create_establishment_taxonomies() {
		// Establishment type
	$est_type_labels = array(
		'name' => _x( 'Establishment Type', 'taxonomy general name' ),
		'singular_name' => _x( 'Establishment Type', 'taxonomy singular name' ),
		'search_items' => __( 'Search Establishment Types' ),
		'all_items' => __( 'All Establishment Types' ),
		'parent_item' => __( 'Parent Establishment Type' ),
		'parent_item_colon' => __( 'Parent Establishment Type:' ),
		'edit_item' => __( 'Edit Establishment Type' ),
		'update_item' => __( 'Update Establishment Type' ),
		'add_new_item' => __( 'Add New Establishment Type' ),
		'new_item_name' => __( 'New Establishment Type' ),
		'menu_name' => __( 'Establishment Types' ),
	);
	$est_type_args = array(
		'hierarchical' => true,
		'labels' => $est_type_labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'establishment', 'with_front' => false ),
	);
	register_taxonomy( 'establishment-type', array( 'establishment' ), $est_type_args );
}
add_action( 'init', 'create_establishment_taxonomies', 0 );