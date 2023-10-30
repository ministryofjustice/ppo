<?php

/*
 * AJAX results for doc type filtering
 */

$ajax_query = new WP_Query($args);

?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php get_template_part( 'templates/content-tile', get_post_format() ); ?>
<?php endwhile; ?>