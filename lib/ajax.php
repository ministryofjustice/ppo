<?php

/*
 * Setup for front-end AJAX
 */

function ajax_scripts()
{
    enqueue_versioned_script('ppo-ajax', '/assets/js/ajax.js', ['jquery']);
    wp_localize_script('ppo-ajax', 'PPOAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'ajax_scripts', 101);


function update_spreadsheet()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'death_spreadsheet';
    $paged = esc_sql($_POST['paged']);
    if ($paged > 1) {
        $page = ($paged - 1) * 50;
        $limit = "LIMIT " . $page;
    } else {
        $limit = "LIMIT 50";
    }
    $sql = "";

    if (isset($_POST['queryParams'])) {
        $params_json = $_POST['queryParams'];
        $params = json_decode(stripslashes($params_json), true);

        foreach ($params as $param) {
            if ($param['value'] != -1) {
                if (empty($sql)) {
                    $sql .= "WHERE ";
                }
                $sql .= esc_sql($param['key']) . " = '" . esc_sql($param['value']) . "'";
                if (end($params) != $param) {
                    $sql .= " AND ";
                }
            }
        }
    }
    $results = $wpdb->get_results("SELECT `case`, death, type, establishment, location, sex, age_group, ethnic_origin, stage FROM $table_name $sql $limit");
    if (count($results) > 0) {
        include(locate_template('templates/content-table.php'));
    } else {
        echo "<h2>No results found</h2>";
    }
    die();
}

add_action('wp_ajax_nopriv_update_spreadsheet', 'update_spreadsheet');
add_action('wp_ajax_update_spreadsheet', 'update_spreadsheet');


function update_tiles()
{
    // Converts dates to datetime for correct ordering
    add_filter('posts_orderby', 'wdw_query_orderby_postmeta_date', 10, 1);

    // Stops the query if no $establishments are found
    $stop_query = false;

    $args_json = $_GET['queryParams'];

    // Decode JSON to array
    $args = json_decode(stripslashes($args_json), true);

    if (isset($args['tax_query'])) {
        foreach ($args['tax_query'] as $i => $tax_query) {
            if ($tax_query['taxonomy'] = "establishment-type") {
                // Remove taxonomy query
                unset($args['tax_query'][$i]);

                // Retrieve matching establishment IDs
                $matching_establishments_args = array(
                    'post_type' => 'establishment',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'establishment-type',
                            'terms' => $tax_query['terms']
                        )
                    )
                );

                $matching_establishments = new WP_Query($matching_establishments_args);

                $matching_establishments_ids = array();
                while ($matching_establishments->have_posts()) {
                    $matching_establishments->the_post();
                    $matching_establishments_ids[] = get_the_ID();
                }

                if (count($matching_establishments_ids) == 0) {
                    $stop_query = true;
                }

                // Add custom field query
                $args['meta_query'][] = array(
                    'key' => 'fii-establishment',
                    'value' => $matching_establishments_ids,
                    'compare' => 'IN',
                    'include_children' => false
                );

                wp_reset_postdata();
            }
        }
    }

    if (isset($args['establishment'])) {
        $establishment_object = get_page_by_title($args['establishment'], OBJECT, 'establishment');
        $establishment_id = $establishment_object->ID;
        $args['meta_query'][] = array(
            'key' => 'fii-establishment',
            'value' => $establishment_id,
            'include_children' => false
        );
    }

    $args['post_status'] = array(
        'publish'
    );

    $ajax_query = new WP_Query($args);
    if ($ajax_query->have_posts() && !$stop_query) {
        ob_start();

        while ($ajax_query->have_posts()) :
            $ajax_query->the_post();
            get_template_part('templates/content-tile', $args['document_type']);
        endwhile;

        $tile_output = ob_get_contents();

        ob_end_clean();
        if ($tile_output) {
            echo $tile_output;
        }

        echo '<script>maxPage = ' . ceil($ajax_query->found_posts / 50) . ';</script>';

        remove_filter('posts_orderby', 'wdw_query_orderby_postmeta_date', 10);
        die();
    } else {
        if ($args['paged'] == 1) {
            echo "<br><h2>No results found</h2>";
        }
        die();
    }
}

add_action('wp_ajax_nopriv_update_tiles', 'update_tiles');
add_action('wp_ajax_update_tiles', 'update_tiles');
