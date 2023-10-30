<?php

function case_types_save( $post_id, $post, $update ) {
	global $pagenow;
	$document_type = get_post_meta($post_id, 'document-type', true);

	if($document_type == 8) {
		if($pagenow == 'admin-ajax.php') {

			$casetypes = get_the_terms($post_id,'case-type');
			$cases = array();
			if(!empty($casetypes)) {
				foreach($casetypes as $casetype) {
					$cases[$casetype->term_id] = "$casetype->term_id";
				}
			}
			update_post_meta( $post_id, 'case-type', $cases);
		}

		if($pagenow == 'post.php') {
			$casetypes = get_post_meta( $post_id, 'case-type');
			$cases = array();
			if(!empty($casetypes[0])) {
				foreach ($casetypes[0] as $key => $value) {
					$cases[] = (int) $value;
				}
			}
			wp_set_post_terms( $post_id, $cases, 'case-type');
		}
	}
}
add_action( 'save_post', 'case_types_save', 10, 3 );


function create_news_post( $post_id, $post, $update ) {
	if (
    wp_is_post_revision($post_id) ||
    wp_is_post_autosave($post_id) ||
    $post->post_type !== 'document' ||
    !isset($_POST['create-news-item']) ||
    !$_POST['create-news-item']
  ) {
    return;
  }

	$content = get_post_meta($post_id, 'document-description', true);
	if (isset($_POST['document-description']) && $content != $_POST['document-description']) {
		$contentValue = $_POST['document-description'];
	} else {
		$contentValue = $content;
	}

	$file = get_post_meta($post_id, 'document-upload', true);
	if (isset($_POST['document-upload']) && $file != $_POST['document-upload']) {
		$fileValue = $_POST['document-upload'];
	} else {
		$fileValue = $file;
	}

	if ($fileValue) {
		$contentValue .= '<p>Click here to read <a href="' . $fileValue . '">' . $post->post_title . '</p>';
	}

  $newsitem = get_post_meta($post_id, 'news_item' );
  if (empty($newsitem) || get_post_status($newsitem) == false || get_post_status($newsitem) == "trash") {
    $post = array(
      'post_content' => $contentValue,
      'post_name' => $post->post_name,
      'post_title' => $post->post_title,
      'post_status' => 'draft',
      'post_type' => 'post',
      'post_date' => $post->post_date,
      'post_category' => array(35),
    );
    $value = wp_insert_post($post);
    if ($value > 0) {
      update_post_meta( $post_id, 'news_item', $value );
    }
  }
}
add_action( 'post_updated', 'create_news_post', 10, 3 );

function newsitem_add_meta_box() {
	add_meta_box(
		'newsitem',
		'Related News Post',
		'newsitem_callback',
		'document',
		'side'
	);
}
add_action( 'add_meta_boxes', 'newsitem_add_meta_box' );

function newsitem_callback( $post ) {
	$news_id = get_post_meta( $post->ID, 'news_item', true );
	if ($news_id && get_post_status( $news_id ) && get_post_status( $news_id ) != "trash") {
    ?>
    <p>A news post was automatically created for this document.</p>
    <a href="<?= get_edit_post_link($news_id) ?>" class="button">Edit News Post</a>
    <?php
	} else {
    ?>
    <label><input type="checkbox" name="create-news-item" value="create-news-item"> Create a related news post</label>
    <p>This will automatically generate a draft news post containing a link to this document.</p>
    <?php
	}
}



function shorturl_add_meta_box() {
	add_meta_box(
		'shorturl',
		'Short URL to Document',
		'shorturl_callback',
		'document',
		'side'
	);
}
add_action( 'add_meta_boxes', 'shorturl_add_meta_box' );

function shorturl_callback( $post ) {
	$value = get_post_meta( $post->ID, 'document-upload', true );
	if($value) {
		$postid = get_attachment_id_from_src( $value );
		echo wp_get_shortlink($postid);
	} else {
		echo "Please upload a document and save the page to see the Short URL.";
	}
}

function my_page_template_redirect()
{
    if( is_attachment() )
    {
        wp_redirect( wp_get_attachment_url() );
        exit();
    }
}
add_action( 'template_redirect', 'my_page_template_redirect' );

/**
 * [remove_document_meta description]
 * Removes document meta boxes for document type
 * @return void
 */
function remove_document_meta() {
	remove_meta_box( 'fii-death-typediv','document', 'side' );
	remove_meta_box( 'document_typediv','document', 'side' );
	remove_meta_box( 'fii-statusdiv','document', 'side' );
	remove_meta_box( 'case-typediv','document', 'side' );

}
add_action( 'admin_menu' , 'remove_document_meta' );

function my_acf_admin_head()
{
	?>
	<script type="text/javascript">
	(function($){

		(function() {
			var document_meta_boxes = [
				{
					// Fatal Incident reports
					document_type_id: 34,
					meta_box: $('#document-fii-meta-box')
				},
				{
					// Learning lessons reports
					document_type_id: 8,
					meta_box: $('#document-llr-meta-box')
				}
			];

			var show_meta_box = function() {
				var selected_type_id = $(this).val();

				document_meta_boxes.forEach(function(box) {
					if (box.document_type_id == selected_type_id) {
						box.meta_box.show();
					}
					else {
						box.meta_box.hide();
					}
				});
			};

			$('#document-type').on('change', show_meta_box).each(show_meta_box);
		})();
		
		(function() {
			var radios = $('input[type=radio][name=show-action-plan]');
			var relatedFields = $('.action-plan-field-wrap');

			var toggleRelatedFields = function() {
				var toggleValue = ( radios.filter(':checked').val() == 'on' );
				relatedFields.toggle(toggleValue);
			};

			radios.on('change', toggleRelatedFields).first().each(toggleRelatedFields);
		})();

	})(jQuery);
	</script>
	<style type="text/css">
		#setting_document-type .select-wrapper { width:99%; }
	</style>

	<?php
}
add_action('admin_footer', 'my_acf_admin_head');

// Custom stylesheet for wp-admin
function custom_admin_styles() {
  wp_enqueue_style('custom-admin-styles', get_template_directory_uri() . '/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'custom_admin_styles');

/**
 * Custom functions
 */
require_once locate_template( '/lib/extend-menus.php' );
require_once locate_template( '/lib/nav-mob.php' );

// Load CPTs
$cpt_declarations = scandir( get_template_directory() . "/lib/cpt/" );
foreach ( $cpt_declarations as $cpt_declaration ) {
	if ( $cpt_declaration[0] != "." )
		require get_template_directory() . '/lib/cpt/' . $cpt_declaration;
}

// Add image sizes
add_image_size( 'admin-list-thumb', 100, 100, false );
add_image_size( 'home-news-thumb', 158, 224, false );
add_image_size( 'document-thumb', 297, 420, false );

/**
 * Filter thumbnail sizes that are generated for PDFs
 *
 * @param array $sizes
 * @return array
 */
function filter_pdf_thumbnail_sizes($sizes) {
  $sizes[] = 'document-thumb';
  return $sizes;
}
add_filter('fallback_intermediate_image_sizes', 'filter_pdf_thumbnail_sizes');

// Add JS
function custom_scripts() {
	wp_register_script( 'equalheight', get_template_directory_uri() . '/assets/js/equalheight.js', array( 'jquery' ), null, false );
	wp_enqueue_script( 'equalheight' );
	wp_register_script( 'jquery.query-object', get_template_directory_uri() . '/assets/js/plugins/jquery.query-object.js', array( 'jquery' ), null, false );
	wp_enqueue_script( 'jquery.query-object' );
//	wp_register_script( 'isotope', get_template_directory_uri() . '/assets/js/vendor/isotope.min.js', array( 'jquery', 'jquery-masonry' ), null, false );
//	wp_enqueue_script( 'isotope' );
	wp_register_script( 'classie', get_template_directory_uri() . '/assets/js/classie.js', array(), null, false );
	wp_enqueue_script( 'classie' );
	wp_register_script( 'mlpushmenu', get_template_directory_uri() . '/assets/js/mlpushmenu.js', array(), null, false );
	wp_enqueue_script( 'mlpushmenu' );
	wp_register_script( 'modernizr.custom', get_template_directory_uri() . '/assets/js/modernizr.custom.js', array( 'jquery' ), null, false );
	wp_enqueue_script( 'modernizr.custom' );
}

add_action( 'wp_enqueue_scripts', 'custom_scripts', 100 );

/* Change OT datepicker format */

function change_ot_date_format() {
	return "dd/mm/yy";
}

add_filter( 'ot_type_date_picker_date_format', 'change_ot_date_format', 20 );

/* Add footer menu */

function create_footer_menu() {
	register_nav_menu( 'footer-navigation', "Footer Menu" );
}

add_action( 'init', 'create_footer_menu' );

/* Setup option tree */
add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_use_theme_options', '__return_true' );
add_filter( 'ot_header_version_text', '__return_null' );

/**
 * Meta Boxes
 */
load_template( trailingslashit( get_template_directory() ) . 'lib/meta-boxes.php' );

/* Add excerpts to pages */
add_action( 'init', 'my_add_excerpts_to_pages' );

function my_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
}

/**
 * Get attachment ID from its URL
 *
 * @param string $url
 * @return bool|int The Attachment ID or FALSE if not found
 */
function get_attachment_id_from_src( $url ) {
	global $wpdb;

  // First: try to find an exact match for the attachment GUID
  $query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid = %s LIMIT 1", $url);
  $id = $wpdb->get_var($query);
  if (!is_null($id)) {
    return (int) $id;
  }

  // Fallback: try and do a fuzzier (but slower) LIKE match
  // Drop everything before /uploads/ in the image src so we can match against different hostnames
  $url_part = substr($url, strpos($url, '/uploads/'));
  $like = '%' . $wpdb->esc_like($url_part);
  $query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid LIKE %s LIMIT 1", $like);
  $id = $wpdb->get_var($query);
  if (!is_null($id)) {
    return (int) $id;
  }

  // Else: attachment not found, return false
  return false;
}

/* Returns friendly filesize */

function file_size_convert( $bytes ) {
	$bytes = floatval( $bytes );
	$arBytes = array(
		0 => array( "UNIT" => "TB", "VALUE" => pow( 1024, 4 ) ),
		1 => array( "UNIT" => "GB", "VALUE" => pow( 1024, 3 ) ),
		2 => array( "UNIT" => "MB", "VALUE" => pow( 1024, 2 ) ),
		3 => array( "UNIT" => "KB", "VALUE" => 1024 ),
		4 => array( "UNIT" => "B", "VALUE" => 1 ),
	);

	foreach ( $arBytes as $arItem ) {
		if ( $bytes >= $arItem["VALUE"] ) {
			$result = $bytes / $arItem["VALUE"];
			$result = strval( round( $result, 2 ) ) . " " . $arItem["UNIT"];
			break;
		}
	}
	if ( isset( $result ) ) {
		return $result;
	} else {
		return false;
	}
}

/**
 * Produce a file meta <span> for the given attachment.
 *
 * @param string $attachment_url
 * @return false|string HTML output
 */
function file_meta($attachment_url) {
	$attachment_id = get_attachment_id_from_src($attachment_url);
	$file_path = get_attached_file($attachment_id);

	if (!$attachment_id || !file_exists($file_path)) {
		return false;
	}

	$filesize = size_format(filesize($file_path));
	$extension = strtoupper(pathinfo($file_path, PATHINFO_EXTENSION));

	return "<span class=\"file-meta\">($extension, $filesize)</span>";
}

/* Add custom filter to Documents listing */

function ppo_add_doc_filters() {
	global $typenow;

	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array( 'document_type' );

	// must set this to the post type you want the filter(s) displayed on
	if ( $typenow == 'document' ) {

		foreach ( $taxonomies as $tax_slug ) {
			$tax_obj = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms( $tax_slug );
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			if ( count( $terms ) > 0 ) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";
				foreach ( $terms as $term ) {

					echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '', '>' . $term->name . ' (' . $term->count . ')</option>';
				}
				echo "</select>";
			}
		}
	}
}

add_action( 'restrict_manage_posts', 'ppo_add_doc_filters' );

// Sets document_type taxonomies to equal drop down value on save
$meta_keys = array( 'document-type', 'fii-death-type', 'fii-status' );

function update_document_type( $meta_id, $object_id, $meta_key, $meta_value ) {
	global $meta_keys;
  if (!isset($meta_keys) || !is_array($meta_keys)) return;
	foreach ( $meta_keys as $current_meta_key ) {
		if ( $meta_key == $current_meta_key ) {
			wp_set_object_terms( $object_id, intval( $meta_value ), $current_meta_key != 'fii-death-type' ? str_replace( "-", "_", $current_meta_key ) : $current_meta_key  );
		}
	}
}

add_action( 'update_post_meta', 'update_document_type', 10, 4 );

function add_document_type( $object_id, $meta_key, $meta_value ) {
	global $meta_keys;
  if (!isset($meta_keys) || !is_array($meta_keys)) return;
	foreach ( $meta_keys as $current_meta_key ) {
		if ( $meta_key == $current_meta_key ) {
			wp_set_object_terms( $object_id, intval( $meta_value ), $current_meta_key != 'fii-death-type' ? str_replace( "-", "_", $current_meta_key ) : $current_meta_key  );
		}
	}
}

add_action( 'add_post_meta', 'add_document_type', 10, 3 );

function fix_datepicker_format( $post_id ) {
	// Standardise date format to dd/mm/yyyy
	foreach ( array( 'document-date', 'fii-death-date' ) as $index ) {
		if ( isset( $_REQUEST[$index] ) ) {
			$date_parts = explode( "/", $_REQUEST[$index] );
			$day = sprintf( "%02d", $date_parts[0] );
			$month = sprintf( "%02d", $date_parts[1] );
			if ( strlen( $date_parts[2] ) == 2 ) {
				$year = $date_parts[2] > 50 ? "19" . $date_parts[2] : "20" . $date_parts[2];
			} else {
				$year = $date_parts[2];
			}
			$new_date = $day . "/" . $month . "/" . $year;
			update_post_meta( $post_id, $index, $new_date );
		}
	}
}

add_action( 'save_post', 'fix_datepicker_format' );

// add editor the privilege to edit theme
$roleObject = get_role( 'editor' );
if ( !$roleObject->has_cap( 'edit_theme_options' ) ) {
	$roleObject->add_cap( 'edit_theme_options' );
}

// Removes sidebar from entire site
add_filter( 'roots_display_sidebar', function() {
	return false;
} );

// Store current menu item ID in global var
function store_current_menu_id( $sorted_menu_items ) {
	foreach ( $sorted_menu_items as $menu_item ) {
		if ( $menu_item->current ) {
			$GLOBALS['current_menu_id'] = $menu_item->ID;
			break;
		}
	}
	return $sorted_menu_items;
}

add_filter( 'wp_nav_menu_objects', 'store_current_menu_id', 10, 2 );

// PPO breadcrumbs
function ppo_breadcrumbs() {
	if ( isset( $GLOBALS['current_menu_id'] ) ) {
		// Get main menu object
		$locations = get_nav_menu_locations();
		$menu = wp_get_nav_menu_object( $locations['primary_navigation'] );

		// Seperator between levels
		$seperator = ">";

		// Level 1 - Home level
		$level1_label = "Home";
		$level1_url = get_home_url();

		// Get remaining levels
		$level4_item = wp_get_nav_menu_items( $menu->term_id, array( 'p' => $GLOBALS['current_menu_id'] ) );
		$level4_label = $level4_item[0]->title;

		$level3_item = wp_get_nav_menu_items( $menu->term_id, array( 'p' => $level4_item[0]->menu_item_parent ) );
		$level3_label = $level3_item[0]->title;

		$level2_item = wp_get_nav_menu_items( $menu->term_id, array( 'p' => $level3_item[0]->menu_item_parent ) );
		$level2_label = $level2_item[0]->title;

		// Output breadcrumb
		$output = "<div id='breadcrumbs'>";
		$output .= "<a href='$level1_url'>$level1_label</a>";
		if ( $level2_label != "Home" ) {
			$output .= " $seperator ";
			$output .= $level2_label;
		}
		if ( $level3_label != "Home" ) {
			$output .= " $seperator ";
			$output .= $level3_label;
		}
		$output .= " $seperator <span class='current'>";
		$output .= $level4_label;
		$output .= "</span></div>";

		echo $output;
	}
}

// OptionTree filter to allow for textarea in list-item
add_filter( 'ot_override_forced_textarea_simple', '__return_true' );

// Force is_search to be set
add_action( 'parse_query', 'search_even_empty' );

function search_even_empty( $query ) {
	if ( isset( $_GET['s'] ) ):
		$query->is_search = true;
	endif;
}

// Removes post types added by Custom Search plugin
remove_filter( 'pre_get_posts', 'cstmsrch_searchfilter' );

// Temporary filter for convering dates to sort by
function wdw_query_orderby_postmeta_date( $orderby ) {
	$new_orderby = str_replace( "wp_postmeta.meta_value", "STR_TO_DATE(wp_postmeta.meta_value, '%d/%m/%Y')", $orderby );
	return $new_orderby;
}

// Add pages to search
function wpshock_search_filter( $query ) {
	$docs_only = (isset( $query->query['docs_only'] ) && $query->query['docs_only']) ? true : false;
	if ( $query->is_search && !is_admin() && !$docs_only ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
	}
	return $query;
}

add_filter( 'pre_get_posts', 'wpshock_search_filter' );

// Exclude 'uncategorized' from post URLs, even if the post is in the 'Uncategorized' category
add_filter('post_link_category', function($cats_0, $cats, $post) {
	// If we're on a category archive page and the post is in the category
	// Then put the current category in the post URL
	if (is_archive() && is_category()) {
		$the_category = get_queried_object();
		if (in_array($the_category, $cats)) {
			return $the_category;
		}
	}

	// Otherwise put any category other than 'Uncategorized' in the URL
	foreach ($cats as $cat) {
		if ($cat->slug !== 'uncategorized') {
			return $cat;
		}
	}

	// Otherwise just use the default
	return $cats_0;
}, 10, 3);

// Filter the category archive page title
add_filter('single_cat_title', function($category_name) {
	return $category_name . ' archive';
});
