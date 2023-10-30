<?php

// Document CPT
function document_cpt_init() {
	$document_labels = array(
		'name' => 'Documents',
		'singular_name' => 'Document',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Document',
		'edit_item' => 'Edit Document',
		'new_item' => 'New Document',
		'all_items' => 'All Documents',
		'view_item' => 'View Document',
		'search_items' => 'Search Documents',
		'not_found' => 'No document found',
		'not_found_in_trash' => 'No document found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Documents'
	);
	$document_args = array(
		'labels' => $document_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => false,
		'query_var' => true,
		'exclude_from_search' => false,
		'rewrite' => array( 'slug' => 'document/%document_type%', 'with_front' => FALSE ),
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
		'supports' => array( 'title', 'thumbnail' ),
		'taxonomies' => array( 'document_type' )
	);
	register_post_type( 'document', $document_args );
}

add_action( 'init', 'document_cpt_init' );

function create_document_taxonomies() {
	// Document Type
	$document_type_labels = array(
		'name' => _x( 'Document Types', 'taxonomy general name' ),
		'singular_name' => _x( 'Document Type', 'taxonomy singular name' ),
		'search_items' => __( 'Search Document Types' ),
		'all_items' => __( 'All Document Types' ),
		'parent_item' => __( 'Parent Document Type' ),
		'parent_item_colon' => __( 'Parent Document Type:' ),
		'edit_item' => __( 'Edit Document Type' ),
		'update_item' => __( 'Update Document Type' ),
		'add_new_item' => __( 'Add New Document Type' ),
		'new_item_name' => __( 'New Document Type Name' ),
		'menu_name' => __( 'Document Types' ),
	);
	$document_type_args = array(
		'hierarchical' => true,
		'labels' => $document_type_labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'document', 'with_front' => false ),
	);
	register_taxonomy( 'document_type', array( 'document' ), $document_type_args );

	// Death Type
	$death_type_labels = array(
		'name' => _x( 'Death Types', 'taxonomy general name' ),
		'singular_name' => _x( 'Death Type', 'taxonomy singular name' ),
		'search_items' => __( 'Search Death Types' ),
		'all_items' => __( 'All Death Types' ),
		'parent_item' => __( 'Parent Death Type' ),
		'parent_item_colon' => __( 'Parent Death Type:' ),
		'edit_item' => __( 'Edit Death Type' ),
		'update_item' => __( 'Update Death Type' ),
		'add_new_item' => __( 'Add New Death Type' ),
		'new_item_name' => __( 'New Death Type Name' ),
		'menu_name' => __( 'Death Types' ),
	);
	$death_type_args = array(
		'hierarchical' => true,
		'labels' => $death_type_labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'document', 'with_front' => false ),
	);
	register_taxonomy( 'fii-death-type', array( 'document' ), $death_type_args );

	// FII status
	$fii_status_labels = array(
		'name' => _x( 'FII Status', 'taxonomy general name' ),
		'singular_name' => _x( 'FII Status', 'taxonomy singular name' ),
		'search_items' => __( 'Search Statuses' ),
		'all_items' => __( 'All Statuses' ),
		'parent_item' => __( 'Parent Status' ),
		'parent_item_colon' => __( 'Parent Status:' ),
		'edit_item' => __( 'Edit Status' ),
		'update_item' => __( 'Update Status' ),
		'add_new_item' => __( 'Add New Status' ),
		'new_item_name' => __( 'New Status Name' ),
		'menu_name' => __( 'FII Status' ),
	);
	$fii_status_args = array(
		'hierarchical' => true,
		'labels' => $fii_status_labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'document', 'with_front' => false ),
	);
	register_taxonomy( 'fii-status', array( 'document' ), $fii_status_args );

	// FII status
	$llr_case_type_labels = array(
		'name' => _x( 'LLR Case Types', 'taxonomy general name' ),
		'singular_name' => _x( 'Case Types', 'taxonomy singular name' ),
		'search_items' => __( 'Search LLR Case Types' ),
		'all_items' => __( 'All LLR Case Types' ),
		'parent_item' => __( 'Parent LLR Case Type' ),
		'parent_item_colon' => __( 'Parent LLR Case Type:' ),
		'edit_item' => __( 'Edit LLR Case Type' ),
		'update_item' => __( 'Update LLR Case Type' ),
		'add_new_item' => __( 'Add New LLR Case Type' ),
		'new_item_name' => __( 'New LLR Case Type Name' ),
		'menu_name' => __( 'Case Type' ),
	);
	$llr_case_type_args = array(
		'hierarchical' => true,
		'labels' => $llr_case_type_labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'document', 'with_front' => false ),
	);
	register_taxonomy( 'case-type', array( 'document' ), $llr_case_type_args );
}

add_action( 'init', 'create_document_taxonomies', 0 );

// Rename Featured Image metabox
function document_image_box() {
  remove_meta_box( 'postimagediv', 'document', 'side' );
  add_meta_box( 'postimagediv', __( 'Document thumbnail' ), 'post_thumbnail_meta_box', 'document', 'side', 'low' );
}

add_action( 'do_meta_boxes', 'document_image_box' );

// Replace "featured image" text in link in metabox
function document_featured_image_link( $content ) {
  global $post_type;
  if ( $post_type == 'document' ) {
    $content = str_replace( __( 'featured image' ), __( 'thumbnail' ), $content );

    if (stristr($content, 'Set thumbnail') !== false) {
      $content = "<p>Thumbnails are auto-generated for PDF documents. Other file types require a thumbnail to be set.</p>" . $content;
    }
  }
  return $content;
}

add_filter( 'admin_post_thumbnail_html', 'document_featured_image_link' );

// Add thumbnail to admin view
// Add the column
function add_document_thumbnail_column( $cols ) {
	$colsstart = array_slice( $cols, 0, 1, true );
	$colsend = array_slice( $cols, 1, null, true );

	$cols = array_merge(
			$colsstart, array( 'doc_thumb' => __( 'Thumbnail' ) ), $colsend
	);
	return $cols;
}

add_filter( 'manage_document_posts_columns', 'add_document_thumbnail_column', 5 );

// Add doc date column
function add_document_date_column( $cols ) {
	$colsstart = array_slice( $cols, 0, 2, true );
	$colsend = array_slice( $cols, 2, null, true );

	$cols = array_merge(
			$colsstart, array( 'doc_date' => __( 'Document Date' ) ), $colsend
	);
	return $cols;
}

add_filter( 'manage_document_posts_columns', 'add_document_date_column', 5 );

// Grab featured-thumbnail size post thumbnail and display it.
function display_document_thumbnail_column( $col, $id ) {
	switch ( $col ) {
		case 'doc_thumb':
		  // Show post thumbnail
      if (has_post_thumbnail()) {
        $img = get_the_post_thumbnail();
      }
      // Fallback to the attachment thumbnail
      else {
        $attachment_id = get_post_meta(get_the_ID(), 'document-upload-attachment-id', true);
        if ($attachment_id) {
          $img = wp_get_attachment_image($attachment_id);
        }
      }
      if (!empty($img)) {
        edit_post_link($img);
      }
			break;
		case 'doc_date':
			echo get_post_meta( get_the_ID(), 'document-date', true );
			break;
	}
}

add_action( 'manage_document_posts_custom_column', 'display_document_thumbnail_column', 5, 2 );

function doc_date_column_register_sortable( $columns ) {
	$columns['doc_date'] = 'doc_date';
	return $columns;
}

add_filter( 'manage_edit-document_sortable_columns', 'doc_date_column_register_sortable' );

function manage_wp_posts_be_qe_posts_clauses( $pieces, $query ) {
	global $wpdb;

	/**
	 * We only want our code to run in the main WP query
	 * AND if an orderby query variable is designated.
	 */
	if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

		// Get the order query variable - ASC or DESC
		$order = strtoupper( $query->get( 'order' ) );

		// Make sure the order setting qualifies. If not, set default as ASC
		if ( !in_array( $order, array( 'ASC', 'DESC' ) ) )
			$order = 'ASC';

		switch ( $orderby ) {

			// If we're ordering by release_date
			case 'doc_date':

				/**
				 * We have to join the postmeta table to
				 * include our release date in the query.
				 */
				$pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'document_date'";

				// Then tell the query to order by our custom field.
				$pieces['orderby'] = "STR_TO_DATE( wp_rd.meta_value,'%d/%m/%Y' ) $order, " . $pieces['orderby'];
				break;
		}
	}
	return $pieces;
}

add_filter( 'posts_clauses', 'manage_wp_posts_be_qe_posts_clauses', 1, 2 );

// Change document_type permalink
function filter_post_type_link( $link, $post ) {
	if ( $post->post_type != 'document' ) {
		return $link;
	}
	if ( $cats = get_the_terms( $post->ID, 'document_type' ) ) {
		$link = str_replace( '%document_type%', array_pop( $cats )->slug, $link );
	}
	return $link;
}

add_filter( 'post_type_link', 'filter_post_type_link', 10, 2 );

/**
 * Redirect document slug to the associated file URL
 * instead of displaying the document-single template.
 */
function redirect_to_document() {
  if (is_single() && get_post_type() == 'document') {
    $file_url = get_post_meta(get_the_ID(), 'document-upload', true);
    if (!empty($file_url)) {
      wp_redirect($file_url, 301);
      exit;
    }
  }
}
add_filter('template_redirect', 'redirect_to_document');

/**
 * Save post meta field 'document-upload-attachment-id'
 * containing the attachment ID for the uploaded document.
 *
 * @param int $post_id
 */
function save_document_upload_id($post_id, $post) {
  if ($post->post_type !== 'document') return;

  $upload_url = get_post_meta($post_id, 'document-upload', true);

  if (!empty($upload_url) && $attachment_id = get_attachment_id_from_src($upload_url)) {
    update_post_meta($post_id, 'document-upload-attachment-id', $attachment_id);
  }
  else {
    delete_post_meta($post_id, 'document-upload-attachment-id');
  }
}
add_action('save_post', 'save_document_upload_id', 10, 2);
