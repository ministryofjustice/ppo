<?php
/*
  Template Name: Filelist Page
 */
get_template_part( 'templates/page', 'header' );
?>

<div class = "entry-content">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</div>

<?php
$files = get_post_meta( get_the_ID(), 'filelist-entries', true );
$file_count = !empty($files) ? count($files) : 0;
?>

<section class="filelist">
	<header><?= $file_count ?> <?= ngettext("file", "files", $file_count) ?> found</header>
	<ul>
		<?php if ($file_count): ?>
			<?php foreach ($files as $file): ?>
				<li>
					<?= $file['date']; ?> –
					<a href="<?= esc_attr($file['file']) ?>"><?= $file['title'] ?></a>
					<?= file_meta($file['file']) ?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</section>