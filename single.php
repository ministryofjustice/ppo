<?php get_template_part( 'templates/content', 'single' ); ?>

<div class="navigation">
	<div class="textleft col-md-6">
		<?php previous_post_link("<span class='glyphicon glyphicon-chevron-left'></span> %link", '%title', true); ?>
	</div>
	<div class="textright col-md-6">
		<?php next_post_link("%link <span class='glyphicon glyphicon-chevron-right'></span>", '%title', true); ?>
	</div>
</div> <!-- end navigation -->
