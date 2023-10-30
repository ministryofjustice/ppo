<div id="loading-spinner"><img
            src="<?php echo get_template_directory_uri(__FILE__) . '/assets/img/ajax-loader.gif'; ?>"></div>

<nav id="sort-filter">
    <div class="report-container">
        <?php
        global $ppo_meta_boxes;
        // Set up filter/sort controls array
        $doc_filters = array(
            'fallback' => array(
                'filters' => array(
                    // Decade starting from year below (x) up to x + 9 of publish date
                    'document-date' => 'years' // TODO: Make this automatic based on data
                ),
                'sort' => array('date'),
                'default' => 'date'
            ),
            'learning-lessons-reports' => array(
                'filters' => array(
                    // Decade starting from year below (x) up to x + 9 of publish date
                    'document-date' => 'years', // TODO: Make this automatic based on data
                    'case-type' => 'all',
                ),
                'sort' => array('date'),
                'default' => 'date'
            ),
            'fii-report' => array(
                'filters' => array(
                    'establishment-type' => 'all', // Provides all taxonomy values for the filter
                    //'fii-death-date' => 'range',
                    'fii-death-type' => 'all',
                    'fii-gender' => 'all',
                    'fii-age' => 'all',
                    'establishment' => 'autocomplete'
                ),
                'sort' => array('website-date', 'date-of-death'),
                'default' => 'website-date'
            )
        );

        // Get document type slug
        $value = get_query_var($wp_query->query_vars['taxonomy']);
        $doc_type_object = get_term_by('slug', $value, $wp_query->query_vars['taxonomy']);
        $doc_type = $doc_type_object->slug;

        // Setup filter and sort arrays
        if (isset($doc_filters[$doc_type])) {
            $filter_object = $doc_filters[$doc_type];
        } else {
            $filter_object = $doc_filters['fallback'];
        }
        $current_filters = $filter_object['filters'];
        $current_sorts = $filter_object['sort'];

        // Output sort controls
        echo "<div class='sorts'><div class='group-label'>Sort</div>";
        foreach ($current_sorts as $sort) {
            $sort_text = str_replace("-", " ", ucfirst($sort));
            $sort_text = str_replace("Website date", "Publish date", $sort_text); //rename title to venacular
            echo "<div class='sort-control " . ($sort == $filter_object['default'] ? "desc" : "off") . "' data-sort-field='$sort'>$sort_text</div>";
        }
        echo "</div>";

        // Output filter controls
        echo "<div class='filters'><div class='group-label'>Filter</div>";
        echo '<div class="filter-controls">';
        foreach ($current_filters as $filter => $values) {
            ?>
            <div class="filter-control">
                <div class='filter-header'>
                    <?php echo ucfirst(str_replace(array("-", "fii ", "establishment type", "death type"),
                        array(" ", "", "location", "cause"), $filter)); ?>
                    <?php if ($filter != "case-type"): ?>
                        <div class='filter-current'>All</div>
                    <?php else: ?>
                        <div class="filter-current">All</div>
                    <?php endif; ?>
                </div>
                <?php
                echo "<div class='filter-options'>";
                if ($values != "autocomplete" && $filter != "case-type") {
                    echo "<div class='filter-option on' data-filter-type='$filter' data-filter-field='-1'>All</div>";
                }
                $extras = null;
                $orig_values = $values;
                $values = array();
                if (!is_array($orig_values)) {
                    $filter_source = '';
                    switch ($orig_values) {
                        case "all":
                            $terms = get_terms($filter, array('hide_empty' => 0, 'orderby' => 'thets_order'));
                            if (!is_wp_error($terms)) {
                                foreach ($terms as $term) {
                                    $values[] = array("label" => $term->name, "option" => $term->term_id);
                                }
                                $filter_source = 'taxonomy';
                            } else {
                                global $ppo_meta_boxes;
                                $arrIt = new RecursiveIteratorIterator(new RecursiveArrayIterator($ppo_meta_boxes));
                                foreach ($arrIt as $sub) {
                                    $subArray = $arrIt->getSubIterator();
                                    if (isset($subArray['id']) && $subArray['id'] === $filter) {
                                        $outputArray = iterator_to_array($subArray);
                                    }
                                }
                                $choices = $outputArray['choices'];
                                foreach ($choices as $choice) {
                                    $values[] = array("label" => $choice['label'], "option" => $choice['value']);
                                }
                                $filter_source = 'meta';
                            }
                            break;
                        case "decades":
                            $extras .= " data-filter-command='decades'";
                            $values = array(1990, 2000, 2010);
                            break;
                        case "years":
                            $extras .= " data-filter-command='years'";
                            for ($y = 2000; $y <= date("Y"); $y++) {
                                $values[] = "$y";
                            }
                            break;
                        case "range":
                            $values = array("start", "end");
                            // NOTE: http://amsul.ca/pickadate.js/date.htm
                            break;
                        case "autocomplete":
                            $values = $wpdb->get_col(
                                "SELECT post_title "
                                . "FROM $wpdb->posts "
                                . "WHERE post_type = '$filter' "
                                . "AND post_status IN ('publish') "
                                . "ORDER BY post_title ASC"
                            );
                            break;
                        default:
                            break;
                    }
                }
                if ($orig_values != 'autocomplete') {
                    foreach ($values as $option) {
                        switch ($orig_values) {
                            case "decades":
                                $contents = $option . " - " . ($option + 9);
                                break;
                            case "years":
                                $contents = $option;
                                break;
                            case "range":
                                $contents = "<input type = 'text' id = '$filter-$option'>";
                                break;
                            case "autocomplete":
                                break;
                            default:
                                $contents = $option['label'];
                                $option = $option['option'];
                        }
                        if ($filter != "case-type") {
                            echo "<div class = 'filter-option' data-filter-type = '$filter' data-filter-field = '$option' data-filter-source='$filter_source'$extras>$contents</div>";
                        } else {
                            echo "<input type='checkbox' name='n-$option' id='n-$option' class='filter-option' data-filter-type = '$filter' data-filter-field = '$option' data-filter-source='$filter_source'$extras>
							<label for='n-$option'>$contents</label>";
                        }
                    }
                } else {
                    ?>
                    <input id="<?php echo $filter; ?>-ac">
                    <div id="<?php echo $filter; ?>-ac-reset" class="ac-reset">Reset</div>
                    <script>
                        $(function () {
                            var availableValues = <?php echo json_encode($values); ?>;
                            $("#<?php echo $filter; ?>-ac").autocomplete({
                                source: availableValues,
                                minLength: 2,
                                select: function (event, ui) {
                                    $(this).parent().parent().find('.filter-current').html(ui.item.label);
                                    var queryParameters = JSON.parse(PPOAjax.queryParams);
                                    queryParameters.establishment = ui.item.label;
                                    PPOAjax.queryParams = JSON.stringify(queryParameters);
                                    update_tiles(PPOAjax.queryParams, true);
                                    $(this).parent().hide().parent().css('border-bottom', 'none');
                                }
                            });
                            $("#<?php echo $filter; ?>-ac-reset").on('click', function () {
                                $("#<?php echo $filter; ?>-ac").val('');
                                $("#<?php echo $filter; ?>-ac").autocomplete('search', '');
                                $(this).parent().parent().find('.filter-current').html('All');
                                var queryParameters = JSON.parse(PPOAjax.queryParams);
                                delete queryParameters.establishment;
                                PPOAjax.queryParams = JSON.stringify(queryParameters);
                                update_tiles(PPOAjax.queryParams, true);
                                $('.ui-autocomplete-input', $(this).parent()).focus();
                            });
                        });</script>
                    <?php
                }
                echo "</div>";
                ?>
            </div>
            <?php
        }
        echo '</div>';
        echo "</div>";
        ?>
    </div>
</nav>

<div class="tile-container">

    <div class="page-header">
        <h1><?php echo get_queried_object()->name; ?></h1>
    </div>

    <?php if (term_description()): ?>
        <div class="description-wrapper">
            <div class="document-type-text collapse in" id="hideaway">
                <?php

                $term_desc = str_replace(array("<p>", "</p>"), "", term_description());
                $term_desc_array = explode("\n", $term_desc);
                $col_count = count($term_desc_array);
                $col_width = (100 / ($col_count - 1));

                foreach ($term_desc_array as $td_para) {
                    if (strlen($td_para) != 0) {
                        echo str_replace("\n", "",
                            "<p style='width:$col_width%;padding: 0 1%;display:inline-block;vertical-align:top;'>$td_para</p>");
                    }
                }

                ?>
            </div>
            <button class="btn btn-primary showHide" type="button" data-toggle="collapse" data-target="#hideaway"
                    aria-expanded="false" aria-controls="hideaway">
                Hide details
            </button>
        </div>
    <?php endif; ?>

    <?php

    //Ordering
    $metakey = 'document-date';

    // Modify query to retrive all docs
    global $wp_query;
    $post_per_page = 50;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = (array_merge($wp_query->query, array(
        'posts_per_page' => $post_per_page,
        'paged' => $paged,
        'order' => "DESC",
        'orderby' => 'meta_value',
        'meta_key' => $metakey
    )));
    echo "<script type = 'text/javascript'>curPage = $paged;
							maxPage = " . ceil($wp_query->found_posts / $post_per_page) . ";
							PPOAjax.queryParams = '" . json_encode($args) . "'</script>";
    ?>

    <div class="live-results tiles"></div>

</div>

<script type="text/javascript">

    $(document).ready(
        function () {
            $contentLoadTriggered = false;
            $(document).scroll(function () {
                if (($(document).scrollTop() + $(window).height()) >= ($(document).height() -
                    150) &&
                    $contentLoadTriggered == false) {
                    $contentLoadTriggered = true;
                    // Modify queryParams
                    var queryParameters = JSON.parse(PPOAjax.queryParams);
                    // Load next results
                    if (curPage < maxPage) {
                        // Increment paged
                        curPage++;
                        $('#loading-spinner').show();
                        queryParameters.paged++;
                        PPOAjax.queryParams = JSON.stringify(queryParameters);
                        update_tiles(PPOAjax.queryParams);
                    }
                }
            });
            // Events for sort controls
            $('#sort-filter').on('click', '.sort-control', function () {
                var sortByValue = $(this).attr('data-sort-field');
                var sortAsc;
                if ($(this).hasClass('asc')) {
                    $('#sort-filter .sort-control').removeClass('asc').removeClass('desc').addClass('off');
                    $(this).addClass('desc').removeClass('off');
                    sortAsc = false;
                } else if ($(this).hasClass('desc')) {
                    $('#sort-filter .sort-control').removeClass('asc').removeClass('desc').addClass('off');
                    $(this).addClass('asc').removeClass('off');
                    sortAsc = true;
                } else {
                    $('#sort-filter .sort-control').removeClass('asc').removeClass('desc').addClass('off');
                    $(this).removeClass('off');
                    $(this).addClass('desc').removeClass('off');
                    sortAsc = false;
                }

                var queryParameters = JSON.parse(PPOAjax.queryParams);
                switch (sortByValue) {
                    case 'date':
                    case 'website-date':
                        sortByValue = 'document-date';
                        break;
                    case 'date-of-death':
                        sortByValue = 'fii-death-date';
                        break;
                    default:
                        sortByValue = '';
                }

                queryParameters.order = (sortAsc ? 'ASC' : 'DESC');
                queryParameters.orderby = 'meta_value';
                queryParameters.meta_key = sortByValue;
                queryParameters.paged = 1;
                PPOAjax.queryParams = JSON.stringify(queryParameters);
                update_tiles(PPOAjax.queryParams, true);
            });
            // Events for filter controls
            $('#sort-filter').on('click', '.filter-option', function () {
                var filterType = $(this).attr('data-filter-type');
                var filterSource = $(this).attr('data-filter-source');
                $(this).addClass('on');
                $(this).parent().children('.filter-option').not(this).removeClass('on');
                if (filterType === 'case-type') {
                    var sList = '';
                    $('input[data-filter-type=\'case-type\']').each(function () {
                        var sThisVal = $(this).next('label').text();
                        if (this.checked == 1) {
                            sList += (sList == '' ? sThisVal : ', ' + sThisVal);
                        }
                    });
                    if (sList == '') {
                        $(this).parent().parent().find('.filter-current').html('All');
                    } else {
                        $(this).parent().parent().find('.filter-current').html(sList);
                    }
                } else {
                    $(this).parent().parent().find('.filter-current').html($(this).html());
                }
                var queryParameters = JSON.parse(PPOAjax.queryParams);
                queryParameters.paged = 1;
                // Check to see if filter already exists
                checkFilter = false;
                filterValue = $(this).attr('data-filter-field');
                if (queryParameters.meta_query) {
                    $.each(queryParameters.meta_query, function (index, value) {
                        if (value.key == filterType) {
                            checkFilter = true;
                            filterIndex = index;
                        }
                    });
                }
                if ($(this).attr('data-filter-field') != -1) { // Check to see if the option is a reset (-1)
                    if (filterType == 'establishment-type') {
                        queryParameters.tax_query = [{
                            taxonomy: filterType,
                            field: 'term_id',
                            terms: $(this).attr('data-filter-field')
                        }];
                    } else if (filterType == 'document-date') {
                        if (checkFilter) {
                            queryParameters.meta_query[filterIndex].value = '/' + $(this).attr('data-filter-field').toString().substr(0, 4);
                        } else {
                            if (queryParameters.meta_query) {
                                queryParameters.meta_query.push(
                                    { relation: 'AND' },
                                    {
                                        key: filterType,
                                        value: '/' + $(this).attr('data-filter-field').toString().substr(0, 4)
                                        , compare: 'LIKE'
                                    }
                                );
                            } else {
                                queryParameters.meta_query = [
                                    { relation: 'AND' },
                                    {
                                        key: filterType,
                                        value: '/' + $(this).attr('data-filter-field').toString().substr(0, 4)
                                        , compare: 'LIKE'
                                    }
                                ];
                            }
                        }
                    } else {
                        if (filterType != 'case-type') {
                            if (checkFilter) { // Filter already set
                                queryParameters.meta_query[filterIndex].value = $(this).attr('data-filter-field');
                            } else { // Filter not set - add to query
                                if (queryParameters.meta_query) {
                                    if ($.inArray('AND', queryParameters.meta_query) == -1) {
                                        queryParameters.meta_query.push('AND');
                                    }
                                    queryParameters.meta_query.push({
                                        key: filterType,
                                        value: $(this).attr('data-filter-field')
                                    });
                                } else {
                                    queryParameters.meta_query = [{
                                        key: filterType,
                                        value: $(this).attr('data-filter-field')
                                    }];
                                }
                            }
                        } else {
                            if ($(this).prop('checked') == true) {
                                if (queryParameters.meta_query) {
                                    queryParameters.meta_query.push({
                                        key: filterType,
                                        value: $(this).attr('data-filter-field'),
                                        compare: 'LIKE'
                                    });
                                } else {
                                    queryParameters.meta_query = [{
                                        key: filterType,
                                        value: $(this).attr('data-filter-field'),
                                        compare: 'LIKE'
                                    }];
                                }
                            } else {
                                if (queryParameters.meta_query.length == 1) {
                                    delete queryParameters.meta_query;
                                } else {
                                    $.each(queryParameters.meta_query, function (index, value) {
                                        if (value.value === filterValue && value.key == filterType) {
                                            queryParameters.meta_query = queryParameters.meta_query.splice(index - 1, 1);
                                            return false;
                                        }
                                    });
                                }
                            }
                        }
                    }
                } else {
                    if (filterType == 'establishment-type') {
                        delete queryParameters.tax_query;
                    } else {
                        delete queryParameters.meta_query;
                    }
                }
                PPOAjax.queryParams = JSON.stringify(queryParameters);
                update_tiles(PPOAjax.queryParams, true);
                $(this).parent().hide().parent().css('border-bottom', 'none');
            });
            // Fix scroll position of sort-filter
            navBottom = 0;
            $(window).on('scroll resize load touchmove', function () {
                if ($(window).width() < 768) {
                    sortTop = '90px';
                    sortReset = '-80px';
                    scrollStart = 120;
                } else {
                    sortTop = (navBottom + 90) + 'px';
                    sortReset = 0;
                    scrollStart = 0;
                }
                // if ($(window).scrollTop() - scrollStart > navBottom) {
                // 	$("#sort-filter").css("top", sortTop).css("position", "fixed").css("margin", "-20px 0").css("padding", "0");
                // 	$(".sorts,.filters").css("margin", "20px 35px");
                // } else {
                // 	$("#sort-filter").css("top", sortReset).css("position", "absolute").css("margin", "-20px -15px").css("padding", "0 15px");
                // 	$(".sorts,.filters").css("margin", "20px");
                // }
            });
            // Navigation for filters
            $('.filter-header').on('click', function (f) {
                menu = $(this).parent().find('.filter-options');
                if (menu.css('display') == 'none') {
                    $('.filters .filter-options').hide();
                    menu.show();
                    $('.ui-autocomplete-input', menu).focus();
                    $(this).parent().parent().find('.filter-control').css('border-bottom', 'none');
                    $(this).parent().css('border-bottom', '8px solid #ccc');
                } else {
                    menu.hide();
                    $(this).parent().css('border-bottom', 'none');
                }
            });
        }
    );

</script>
