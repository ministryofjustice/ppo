<?php

/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.min.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-3.3.1.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr-2.7.0.min.js
 * 3. /theme/assets/js/main.min.js (in footer)
 */
function roots_scripts()
{
    global $wp_styles;

    $get_assets = file_get_contents(get_template_directory() . '/dist/mix-manifest.json');
    $assets = json_decode($get_assets, true);
    $dist = get_template_directory_uri() . '/dist';
    $assets = array(
        'css'         => $dist . $assets['/css/main.min.css'],
        'fonts'       => $dist . $assets['/css/fonts.css'],
        'fontello-ie' => $dist . $assets['/css/fontello-ie7.css'],
        'ie7'         => $dist . $assets['/css/ie7.css'],
        'ie7and8'     => $dist . $assets['/css/ie7and8.css'],
        'old-ie'      => $dist . $assets['/css/old-ie.css'],
        'jquery-ui'   => $dist . $assets['/css/jquery-ui.min.css'],
        'js'          => $dist . $assets['/js/main.min.js'],
        'modernizr'   => $dist . $assets['/js/modernizr.js'],
        'jquery'      => '//ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js',
        'jq-migrate'  => '//code.jquery.com/jquery-migrate-3.0.1.min.js',
        'g-fonts'     => '//fonts.googleapis.com/css?family=Montserrat:400,700&display=swap'
    );
    wp_enqueue_style('fonts', $assets['fonts']);
    wp_enqueue_style('roots_main', $assets['css'], ['fonts']);
    wp_enqueue_style('wpb-google-fonts', $assets['g-fonts'], false);

    // jQueryUI theme
    wp_enqueue_style("jquery-ui-css", $assets['jquery-ui']);
    wp_enqueue_style('fontello-ie7', $assets['fontello-ie'], array('roots_main'));
    $wp_styles->add_data('fontello-ie7', 'conditional', 'lt IE 8');

    wp_enqueue_style('ie7', $assets['ie7'], array('roots_main'));
    $wp_styles->add_data('ie7', 'conditional', 'lt IE 8');

    wp_enqueue_style('ie7and8', $assets['ie7and8'], array('roots_main'));
    $wp_styles->add_data('ie7and8', 'conditional', 'lt IE 9');

    wp_enqueue_style('old-ie', $assets['old-ie'], array('roots_main'));
    $wp_styles->add_data('old-ie', 'conditional', 'lt IE 10');

    // jQuery is loaded using the same method from HTML5 Boilerplate:
    // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
    // It's kept in the header instead of footer to avoid conflicts with plugins.
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', $assets['jquery'], array(), '3.4.0', false);

        wp_deregister_script('jquery-migrate');
        wp_enqueue_script('jquery-migrate', $assets['jq-migrate'], ['jquery'], '3.0.1', false);

        add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
    }

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_enqueue_script('modernizr', $assets['modernizr'], ['jquery'], null, false);
    wp_enqueue_script('roots_scripts', $assets['js'], ['jquery', 'modernizr'], null, true);
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('jquery-ui-autocomplete');
}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null)
{
    static $add_jquery_fallback = false;

    if ($add_jquery_fallback) {
        echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery.min.js"><\/script>\')</script>' . "\n";
        $add_jquery_fallback = false;
    }

    if ($handle === 'jquery') {
        $add_jquery_fallback = true;
    }

    return $src;
}

add_action('wp_head', 'roots_jquery_local_fallback');
