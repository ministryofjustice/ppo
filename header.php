<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>

    <!-- Google fonts -->
    <link href='<?php echo get_template_directory_uri(); ?>/assets/css/GentiumBookBasic.css' rel='stylesheet' type='text/css'>

    <!--<link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo( 'name' ); ?> Feed" href="<?php echo esc_url( get_feed_link() ); ?>">-->

    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/font-awesome-ie7.min.css">
    <![endif]-->

    <!--[if IE 8]>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/EventHelpers.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/cssQuery-p.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/sylvester.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/cssSandpaper.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/IE9.js"></script>
    <![endif]-->

    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/respond.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/json2.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/assets/js/html5shiv.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
</head>

<body <?php body_class(); ?>>

<?php
if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Open the body tag, pull in any hooked triggers.
	 **/
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
wp_body_open();
?>

<a class="skip-main" href="#content">Skip to main content</a>
<div id="shade-overlay" class=""></div>

<!--[if lt IE 9]>
<div class="alert alert-warning">
    <?php _e( 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience, such as Firefox or Chrome.', 'roots' ); ?>
</div>
<![endif]-->
<?php
include "lib/emergency-banner.php";
?>
<header class="banner navbar navbar-static-top" role="banner">
    <div class="nav-container">
        <a class="brand" href="<?php echo home_url(); ?>/">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/ppo-logo.svg" alt="<?php bloginfo( 'name' ); ?>" class="ppo-logo-svg">
            <!--[if lte IE 8]>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/ppo-logo.png" alt="<?php bloginfo( 'name' ); ?>" class="ppo-logo-png">
            <![endif]-->
        </a>
        <nav class="collapse navbar-collapse" role="navigation">
            <?php
            if ( has_nav_menu( 'primary_navigation' ) ) :
                wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav', 'depth' => 3 ) );
            endif;
            ?>
        </nav>
    </div>
    <div class="container">
        <div class="navbar-header">
            <button id="trigger" type="button" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
</header>
<div class="mp-pusher" id="mp-pusher">
    <nav id="mp-menu" class="mp-menu">
        <?php
        if ( has_nav_menu( 'primary_navigation' ) ) :
            wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => false, 'depth' => 3, 'walker' => new Mob_Nav_Walker ) );
        endif;
        ?>
    </nav>

</div>
<?php get_template_part('templates/feedback-banner'); ?>
