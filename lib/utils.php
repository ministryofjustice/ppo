<?php
/**
 * Utility functions
 */
function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}

function is_element_empty($element) {
  $element = trim($element);
  return empty($element) ? false : true;
}

function enqueue_versioned_style($handle, $src = '', $deps = array(), $media = 'all') {
	$asset_path = get_template_directory() . $src;
	$asset_url = get_template_directory_uri() . $src;
	$asset_modified_time = filemtime($asset_path);
	wp_enqueue_style($handle, $asset_url, $deps, $asset_modified_time, $media);
}

function enqueue_versioned_script($handle, $src = '', $deps = array(), $in_footer = false) {
	$asset_path = get_template_directory() . $src;
	$asset_url = get_template_directory_uri() . $src;
	$asset_modified_time = filemtime($asset_path);
	wp_enqueue_script($handle, $asset_url, $deps, $asset_modified_time, $in_footer);
}
