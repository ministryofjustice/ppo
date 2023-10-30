<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$anon_reports = new WP_Query( array(
	'post_type' => 'document',
	'posts_per_page' => -1,
	'tax_query' => array(
		array(
			'taxonomy' => 'document_type',
			'field' => 'slug',
			'terms' => 'fii-report'
		)
	),
	'date_query' => array(
		array(
			'after' => '1 week ago'
		)
	)
		) );
?>

<h1>Anonymised reports published in the last seven days</h1>

<?php
if ( $anon_reports->have_posts() ) { ?>
	<div class="tile-container">
		<div class="tiles">
			<?php while ( $anon_reports->have_posts() ) {
				$anon_reports->the_post();
				get_template_part( 'templates/content-tile', 'fii-report' );
			} ?>
		</div>
	</div>
<?php } else {
	?>
	<h2>There have been no reports published in the last seven days.</h2>
<?php } ?>
