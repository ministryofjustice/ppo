<?php

/**
 * Initialize the meta boxes. 
 */
add_action( 'admin_init', 'custom_meta_boxes' );

function get_template_pages( $template_name ) {
	$faq_pages = get_pages( array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'template-' . $template_name . '.php',
		'hierarchical' => 0
			) );
	$faq_array = array();
	foreach ( $faq_pages as $faq_page ) {
		$faq_array[] = $faq_page->post_name;
	}
	return $faq_array;
}

function custom_meta_boxes() {
	global $ppo_meta_boxes;
	$admin_post_id = (filter_input( INPUT_GET, 'post' ) ? filter_input( INPUT_GET, 'post' ) : 0);

	// Hacky way to stop meta-box appearing on other pages, yet still be processed when submitted
	// TODO: Refactor into seperate function (and possibly add to wp-util or branch option-tree and include there)
	// function filter_metabox($post_id,$metabox_array) {
	$post_details = get_post( $admin_post_id );
	$post_exists = isset( $post_details );

	if ( is_edit_page() ) {
		foreach ( $ppo_meta_boxes as $meta_box ) {
			if ( !isset( $meta_box['disabled'] ) || !$meta_box['disabled'] ) {
				$show_metabox = false;
				$has_slug = isset( $meta_box['slug'] );
				$has_control = isset( $meta_box['control'] );
				if ( !$has_control && !$has_slug || isset( $_POST['_wpnonce'] ) ) {
					$show_metabox = true;
				} elseif ( $post_exists ) {
					if ( $has_slug ) { // Controls visibility by slug
						if (
								$post_details->post_name === $meta_box['slug'] ||
								(is_array( $meta_box['slug'] ) &&
								in_array( $post_details->post_name, $meta_box['slug'] ) )
						) {
							$show_metabox = true;
						}
					} elseif ( $has_control ) { // Controls visibility by taxonomy (but need to save first)
						foreach ( $meta_box['control'] as $control ) {
							$post_taxonomy = wp_get_post_terms( $admin_post_id, $control['taxonomy'] );
							if ( $post_taxonomy && $post_taxonomy[0]->slug == $control['value'] ) {
								$show_metabox = true;
							}
						}
					}
				}
				// Show metabox 
				if ( $show_metabox ) {
					ot_register_meta_box( $meta_box );
				}
			}
		}
	}
}

function is_edit_page( $new_edit = null ) {
	global $pagenow;
	//make sure we are on the backend
	if ( !is_admin() ) {
		return false;
	}


	if ( $new_edit == "edit" ) {
		return in_array( $pagenow, array( 'post.php', ) );
	} elseif ( $new_edit == "new" ) { //check for new post page
		return in_array( $pagenow, array( 'post-new.php' ) );
	} else { //check for either new or edit
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}
}
