<article <?php post_class(); ?>>
	<?php if (has_post_thumbnail()): ?>
		<a class="news-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'home-news-thumb' ); ?></a>
	<?php endif; ?>
	<header>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php
		if ( !is_search()) {
			get_template_part( 'templates/entry-meta' );
		} else {
			
		}
		?>
	</header>
	<div class="entry-summary">

		<?php
		if ( !is_search() ) {
			the_excerpt();
		} else {
			// Configuration
			$max_length = 400; // Max length in characters
			$min_padding = 30; // Min length in characters of the context to place around found search terms
			// Load content as plain text
			global $wp_query, $post;
			$content = (!post_password_required( $post ) ? strip_tags( preg_replace( array( "/\r?\n/", '@<\s*(p|br\s*/?)\s*>@' ), array( ' ', "\n" ), apply_filters( 'the_content', $post->post_content ) ) ) : '');

			// Search content for terms
			$terms = $wp_query->query_vars['search_terms'];
			if ( preg_match_all( '/' . str_replace( '/', '\/', join( '|', $terms ) ) . '/i', $content, $matches, PREG_OFFSET_CAPTURE ) ) {
				$padding = max( $min_padding, $max_length / (2 * count( $matches[0] )) );

				// Construct extract containing context for each term
				$output = '';
				$last_offset = 0;
				foreach ( $matches[0] as $match ) {
					list($string, $offset) = $match;
					$start = $offset - $padding;
					$end = $offset + strlen( $string ) + $padding;
					// Preserve whole words
					while ( $start > 1 && preg_match( '/[A-Za-z0-9\'"-]/', $content{intval(round($start)) - 1} ) )
						$start--;
					while ( $end < strlen( $content ) - 1 && preg_match( '/[A-Za-z0-9\'"-]/', $content{intval(round($end))} ) )
						$end++;
					$start = max( $start, $last_offset );
					$context = substr( $content, $start, $end - $start );
					if ( $start > $last_offset )
						$context = '...' . $context;
					$output .= $context;
					$last_offset = $end;
				}

				if ( $last_offset != strlen( $content ) - 1 )
					$output .= '...';
			} else {
				$output = $content;
			}

			if ( strlen( $output ) > $max_length ) {
				$end = $max_length - 3;
				while ( $end > 1 && preg_match( '/[A-Za-z0-9\'"-]/', $output{$end - 1} ) )
					$end--;
				$output = substr( $output, 0, $end ) . '...';
			}

			// Highlight matches
			$context = nl2br( preg_replace( '/' . str_replace( '/', '\/', join( '|', $terms ) ) . '/i', '<span class="highlight">$0</span>', $output ) );
			?>

			<p class="search_result_context">
				<?php echo $context ?>
			</p>
			<p>
				<a href="<?php the_permalink() ?>" rel="bookmark">Read this entry</a>
			</p>
			<?php
		}
		?>
	</div>
</article>
