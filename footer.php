<footer class="content-info container" role="contentinfo">
	<div class="row">

		<?php $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
		<nav class="footer-social-links">
			<ul>
				<li><a href="https://www.facebook.com/sharer.php?u=<?= urlencode($current_url) ?>" target="_blank" class="social-icon" title="Share this page on Facebook"><i class="icon-facebook"></i></a></li>
				<li><a href="https://twitter.com/share?url=<?= urlencode($current_url) ?>" target="_blank" class="social-icon" title="Share this page on Twitter"><i class="icon-twitter"></i></a></li>
				<li><a href="https://plus.google.com/share?url=<?= urlencode($current_url) ?>" target="_blank" class="social-icon" title="Share this page on Google+"><i class="icon-gplus"></i></a></li>
			</ul>
		</nav>

		<div>
			<?php dynamic_sidebar( 'sidebar-footer' ); ?>
			<?php
			if ( has_nav_menu( 'footer-navigation' ) ) :
				wp_nav_menu( array( 'theme_location' => 'footer-navigation', 'menu_class' => 'nav nav-pills' ) );
			endif;
			?>
		</div>



	</div>
</footer>

<script>
	new mlPushMenu(document.getElementById('mp-menu'), document.getElementById('trigger'));
</script>

<?php wp_footer(); ?>
</body>
</html>
