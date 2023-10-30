<div class="row">

	<?php get_search_form(); ?>

	<div class="col-lg-6 col-sm-12 tile-container">
		<h2>Documents</h2>
		<?php
		// Create search query to search for documents and pages
		$args = array(
			'post_type' => 'document',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			's' => get_search_query(),
			'docs_only' => true
		);
		$matching_docs = new WP_Query( $args );
		?>
		<?php if ( !$matching_docs->have_posts() ) : ?>
			<div class="alert alert-warning">
				<?php _e( 'Sorry, no matching documents were found.', 'roots' ); ?>
			</div>
		<?php endif; ?>
		<?php
		while ( $matching_docs->have_posts() ) : $matching_docs->the_post();
			?>
			<?php get_template_part( 'templates/content-tile', get_post_format() ); ?>
		<?php endwhile; ?>

	</div>

	<div class="col-lg-6 col-sm-12">
		<h2>Pages</h2>
		<?php
		// Modify search query to search for documents and pages
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			's' => get_search_query()
		);
		$matching_pages = new WP_Query( $args );
		if ( !$matching_pages->have_posts() ) :
			?>
			<div class="alert alert-warning">
				<?php _e( 'Sorry, no matching pages were found.', 'roots' ); ?>
			</div>
			<?php
		endif;
		while ( $matching_pages->have_posts() ) : $matching_pages->the_post();
			?>
			<?php get_template_part( 'templates/content', get_post_format() ); ?>
		<?php endwhile; ?>

		<?php if ( $matching_pages->max_num_pages > 1 ) : ?>
			<nav class="post-nav">
				<ul class="pager">
					<li class="previous"><?php next_posts_link( __( '&larr; Older posts', 'roots' ) ); ?></li>
					<li class="next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'roots' ) ); ?></li>
				</ul>
			</nav>
		<?php endif; ?>

	</div>

</div>