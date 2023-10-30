<?php

/**
 * Initialize the custom Theme Options.
 */
add_action( 'admin_init', 'custom_theme_options' );

/**
 * Build the custom settings & update OptionTree.
 *
 * @return    void
 * @since     2.3.0
 */
function custom_theme_options() {

	/* OptionTree is not loaded yet */
	if ( !function_exists( 'ot_settings_id' ) )
		return false;

	/**
	 * Get a copy of the saved settings array. 
	 */
	$saved_settings = get_option( ot_settings_id(), array() );

	/**
	 * Custom settings array that will eventually be 
	 * passes to the OptionTree Settings API Class.
	 */
	$homepage_sections = array();
	$homepage_button_settings = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$homepage_sections[] = array(
			'id' => 'homepage_large_nav' . $i,
			'title' => __( 'Homepage Button ' . $i, 'ppo' )
		);
		// Title
		$homepage_button_settings[] = array(
			'id' => 'homepage_nav_title' . $i,
			'label' => __( 'Title', 'ppo' ),
			'desc' => __( 'The heading at the top of each button', 'ppo' ),
			'type' => 'text',
			'section' => 'homepage_large_nav' . $i
		);
		// Text
		$homepage_button_settings[] = array(
			'id' => 'homepage_nav_text' . $i,
			'label' => __( 'Description', 'ppo' ),
			'desc' => __( 'A brief description of that area of the website', 'ppo' ),
			'type' => 'textarea-simple',
			'section' => 'homepage_large_nav' . $i
		);
		// URL
		$homepage_button_settings[] = array(
			'id' => 'homepage_nav_url' . $i,
			'label' => __( 'URL', 'ppo' ),
			'desc' => __( 'The URL of the page the button will link to', 'ppo' ),
			'type' => 'text',
			'section' => 'homepage_large_nav' . $i
		);
		// Icon/image
		$homepage_button_settings[] = array(
			'id' => 'homepage_nav_image' . $i,
			'label' => __( 'Image', 'ppo' ),
			'desc' => __( 'The icon or image displayed on the button', 'ppo' ),
			'type' => 'upload',
			'section' => 'homepage_large_nav' . $i
		);
		// Image alt text
		$homepage_button_settings[] = array(
			'id' => 'homepage_nav_image_alt' . $i,
			'label' => __( 'Alt', 'ppo' ),
			'desc' => __( 'The alt text for the image', 'ppo' ),
			'type' => 'text',
			'section' => 'homepage_large_nav' . $i
		);
	}

	$custom_settings = array(
		'sections' => $homepage_sections,
		'settings' => $homepage_button_settings
	);

	/* allow settings to be filtered before saving */
	$custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

	/* settings are not the same update the DB */
	if ( $saved_settings !== $custom_settings ) {
		update_option( ot_settings_id(), $custom_settings );
	}

	/* Lets OptionTree know the UI Builder is being overridden */
	global $ot_has_custom_theme_options;
	$ot_has_custom_theme_options = true;
}
