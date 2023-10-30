<?php $table_name = $wpdb->prefix . 'death_spreadsheet'; ?>
<?php $columns = array("type", "location", "sex", "age_group", "ethnic_origin", "stage"); ?>
<div id="loading-spinner"><img src="<?php echo get_template_directory_uri( __FILE__ ) . '/assets/img/ajax-loader.gif'; ?>"></div>
<nav id="sort-filter">
  <div class="filters">
    <div class="group-label">Filter</div>
    <?php foreach($columns as $column): ?>
      <?php $results = $wpdb->get_results("SELECT DISTINCT `$column` FROM $table_name ORDER BY `$column` ASC"); ?>
      <?php if($results): ?>
        <div class="filter-control">
          <div class="filter-header">
            <?= str_replace("_", " ", ucwords($column) );?>
            <div class="filter-current">All</div>
          </div>
          <div class="filter-options">
            <div class="filter-option on" data-filter-type="<?= $column ?>" data-filter-field="-1">All</div>
            <?php foreach($results as $result): ?>
              <?php if(!empty($result->$column)): ?>
                <div class="filter-option on" data-filter-type="<?= $column ?>" data-filter-field="<?= $result->$column ?>"><?= $result->$column ?></div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</nav>

<?php get_template_part('templates/page', 'header'); ?>
<?php $posts_per_page = 50; ?>
<?php $results = $wpdb->get_results("SELECT `case`, death, type, establishment, location, sex, age_group, ethnic_origin, stage FROM $table_name LIMIT $posts_per_page"); ?>
<div class="spreadsheet"><?php include(locate_template('templates/content-table.php')); ?></div>
<?php $count = $wpdb->get_results("SELECT count(*) FROM $table_name"); ?>


<script type="text/javascript">
  jQuery(document).ready(function($) {
    var maxPage = <?= ceil( $count[0]->{'count(*)'} / 50 ) ?>;
    var queryParameters = [];
    var queryParams;
    var paged = 1;
    $('.filter-header').on('click', function(f) {
      menu = $(this).parent().find(".filter-options");
      if (menu.css('display') == 'none') {
        $('.filters .filter-options').hide();
        menu.show();
        $(".ui-autocomplete-input", menu).focus();
        $(this).parent().parent().find(".filter-control").css("border-bottom", "none");
        $(this).parent().css("border-bottom", "8px solid #ccc");
      } else {
        menu.hide();
        $(this).parent().css("border-bottom", "none");
      }
    });
    $('#sort-filter').on('click', '.filter-option', function() {
      var filterType = $(this).attr('data-filter-type');
      var filterField = $(this).attr('data-filter-field');
      $(this).addClass('on');
      $(this).parent().children('.filter-option').not(this).removeClass('on');
      $(this).parent().parent().find('.filter-current').html($(this).html());

      checkFilter = false;
      if (queryParameters) {
        $.each(queryParameters, function(index, value) {
          if (value.key == filterType) {
            checkFilter = true;
            filterIndex = index;
          }
        });
      }

      if(checkFilter) {
        queryParameters[filterIndex].value = filterField;
      } else if(queryParameters) {
        queryParameters.push({key: filterType, value: filterField});
      } else {
        queryParameters = [{key: filterType, value: filterField}];
      }
      queryParams = JSON.stringify(queryParameters);
      paged = 1;
      update_spreadsheet(queryParams, paged);

      $(this).parent().hide().parent().css("border-bottom", "none");
    });

    $contentLoadTriggered = false;
    $(document).scroll(function() {
      if (($(document).scrollTop() + $(window).height()) >= ($(document).height() -
          150) &&
          $contentLoadTriggered == false)
      {
        $contentLoadTriggered = true;
        if (paged < maxPage) {
          // Increment paged
          $("#loading-spinner").show();
          paged++;
          update_spreadsheet(queryParams, paged);
        }
      }
    });
  });
</script>
