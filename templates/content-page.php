<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
	<?php wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) ); ?>

	<?php
	// Set up the objects needed
	$my_wp_query = new WP_Query();
	$all_wp_pages = $my_wp_query->query( array( 'post_type' => 'page', 'posts_per_page' => -1, 'order' => 'ASC', 'order_by' => 'position' ) );

	$page_parents = get_post_ancestors( get_the_ID() );

	$locations = get_nav_menu_locations();
	$menu = wp_get_nav_menu_object( $locations['primary_navigation'] );
	$cur_menu_item = wp_get_nav_menu_items( $menu->term_id, array( 'p' => $GLOBALS['current_menu_id'] ) );
	$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
	if ( !empty( $menu_items ) && !is_page(array('contact-us','cookies','terms-conditions') )) {
		echo "<div class='child-pages'>";
		echo '<ul class="section-submenu">';
		foreach ( $menu_items as $menu_item ) {
			if ( $menu_item->menu_item_parent == $cur_menu_item[0]->menu_item_parent && $menu_item->ID != $cur_menu_item[0]->ID ) {
				echo '<li><a href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
			}
		}
		echo '</ul>';
		echo '</div>';
	}
	?>

<?php endwhile; ?>