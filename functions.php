<?php
/**
 * Roots includes
 */
require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/wrapper.php');         // Theme wrapper class
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/titles.php');          // Page titles
require_once locate_template('/lib/cleanup.php');
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/gallery.php');         // Custom [gallery] modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/relative-urls.php');   // Root relative URLs
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/theme-options.php');   // Theme options
require_once locate_template('/lib/ajax.php');			 // AJAX setup
require_once locate_template('/lib/custom.php');          // Custom functions
require_once locate_template('/lib/meta-box-array.php');  // Meta box array
require_once locate_template('/lib/banner-settings.php');  // banner settings
require_once locate_template('/lib/emergency-banner-settings.php');  // emergency banner settings

/**
 * WP-CLI commands
 */
if (class_exists('WP_CLI')) {
  require_once locate_template('/lib/cli/migrate-documents.php');
}
