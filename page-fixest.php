<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$fii_docs = new WP_Query( array(
	'post-type' => 'document',
	'posts_per_page' => -1,
	'tax_query' => array(
		array(
			'taxonomy' => 'document_type',
			'field' => 'slug',
			'terms' => 'fii-report'
		)
	)
		)
);

//print_r($fii_docs);

if ( $fii_docs->have_posts() ) {
	while ( $fii_docs->have_posts() ) {
		$fii_docs->the_post();
		$est_name = get_metadata('post', get_the_ID(), 'fii-establishment-name', true);
		$est_obj = get_page_by_title($est_name, OBJECT, 'establishment');
		if (!$est_obj) {
			echo "<p>No establishment record for $est_name - document ID " . get_the_ID() . "</p>";
		} else {
			update_metadata('post',  get_the_ID(),'fii-establishment',$est_obj->ID);
		}
//		echo $est_name . " - " . $est_obj->ID . "<br>";
	}
} else {
	// no posts found
}