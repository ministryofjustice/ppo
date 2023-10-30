<?php

class Mob_Nav_Walker extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<div class=\"mp-level\">\n";
		$indent = str_repeat( "\t", $depth + 1 );
		$output .= "<h2 class=\"icon\">" . "</h2>"; // Need to add header for each menu
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth + 1 );
		$output .= "$indent</ul>\n";
		$indent = str_repeat( "\t", $depth + 1 );
		$output .= "\n$indent</div>\n";
	}

}

function add_pushmenu_class( $nav_menu, $args ) {
	if ( get_class($args->walker) == "Mob_Nav_Walker" ) {
		$args->items_wrap = "<div class=\"mp-level\" data-level=\"1\"><h2 class=\"icon\"></h2>" . $args->items_wrap . "</div>";
	}
}

add_filter( 'pre_wp_nav_menu', 'add_pushmenu_class', 10, 2 );
