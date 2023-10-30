<?php

/*
 * These actions are performed after a file has been imorted using the Wordpress/XML importer
 */

function after_import() {
	global $meta_keys;
	$documents = new WP_Query( array( 'post_type' => 'document' ) );
	foreach ( $documents as $document ) {
		foreach ( $meta_keys as $current_meta_key ) {
			update_document_type( null, $document->ID, $current_meta_key, get_metadata( 'post', $document->ID, $current_meta_key, true ) );
		}
	}
}

add_action( 'import_end', 'after_import' );
