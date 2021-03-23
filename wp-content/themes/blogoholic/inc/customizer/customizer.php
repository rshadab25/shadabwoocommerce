<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 */

/**
 * Get all the default values of the theme mods.
 */
function blogoholic_get_default_mods() {
	$blogoholic_default_mods = array(
		// Footer copyright
		'blogoholic_copyright_txt' => sprintf( esc_html__( 'Theme: %1$s by %2$s.', 'blogoholic' ), 'blogoholic', '<a href="' . esc_url( 'http://moralthemes.com/' ) . '">Moral Themes</a>' ),

		// Homepage sortable sections
		'blogoholic_sort_home_sections' => array( 'banner', 'lifestyle', 'culture', 'featured', 'blog' ),
	);

	return apply_filters( 'blogoholic_default_mods', $blogoholic_default_mods );
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blogoholic_customize_register( $wp_customize ) {

	$default = blogoholic_get_default_mods();

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'blogoholic_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'blogoholic_customize_partial_blogdescription',
		) );
	}

	// Header text display setting
	$wp_customize->add_setting(	
		'blogoholic_header_text_display',
		array(
			'sanitize_callback' => 'blogoholic_sanitize_checkbox',
			'default' => true,
			'transport'	=> 'postMessage',
		)
	);

	$wp_customize->add_control(
		'blogoholic_header_text_display',
		array(
			'section'		=> 'title_tagline',
			'type'			=> 'checkbox',
			'label'			=> esc_html__( 'Display Site Title and Tagline', 'blogoholic' ),
		)
	);

	
	// Home sections panel
	$wp_customize->add_panel(
		'blogoholic_home_panel',
		array(
			'title' => esc_html__( 'Homepage Options', 'blogoholic' ),
			'priority' => 105
		)
	);

	// Your latest posts title setting
	$wp_customize->add_setting(	
		'blogoholic_your_latest_posts_title',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => esc_html__( 'Blogs', 'blogoholic' ),
			'transport'	=> 'postMessage',
		)
	);

	$wp_customize->add_control(
		'blogoholic_your_latest_posts_title',
		array(
			'section'		=> 'static_front_page',
			'label'			=> esc_html__( 'Title:', 'blogoholic' ),
			'active_callback' => 'blogoholic_is_latest_posts'
		)
	);

	$wp_customize->selective_refresh->add_partial( 
		'blogoholic_your_latest_posts_title', 
		array(
	        'selector'            => '.home.blog #page-header .page-title',
			'render_callback'     => 'blogoholic_your_latest_posts_partial_title',
    	) 
    );

	/**
	 * 
	 * Front Section
	 * 
	 */ 

    //banner
    require get_parent_theme_file_path( '/inc/customizer/banner.php' );

    //lifestyle
    require get_parent_theme_file_path( '/inc/customizer/lifestyle.php' );

    //culture
    require get_parent_theme_file_path( '/inc/customizer/culture.php' );

    //featured
    require get_parent_theme_file_path( '/inc/customizer/featured.php' );

    //blog
    require get_parent_theme_file_path( '/inc/customizer/blog.php' );


	// Advance Setting
	require get_parent_theme_file_path( '/inc/customizer/advance-setting.php' );
}
add_action( 'customize_register', 'blogoholic_customize_register' );


// Sanitize Callback
require get_parent_theme_file_path( '/inc/customizer/sanitize-callback.php' );

// active Callback
require get_parent_theme_file_path( '/inc/customizer/active-callback.php' );

// Partial Refresh
require get_parent_theme_file_path( '/inc/customizer/partial-refresh.php' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blogoholic_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blogoholic_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blogoholic_customize_preview_js() {
	wp_enqueue_script( 'blogoholic-customizer', get_theme_file_uri( '/assets/js/customizer.js' ), array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'blogoholic_customize_preview_js' );

/**
 * Binds JS handlers for Customizer controls.
 */
function blogoholic_customize_control_js() {


	wp_enqueue_style( 'blogoholic-customize-style', get_theme_file_uri( '/assets/css/customize-controls.css' ), array(), '20151215' );

	wp_enqueue_script( 'blogoholic-customize-control', get_theme_file_uri( '/assets/js/customize-control.js' ), array( 'jquery', 'customize-controls' ), '20151215', true );
	$localized_data = array( 
		'refresh_msg' => esc_html__( 'Refresh the page after Save and Publish.', 'blogoholic' ),
		'reset_msg' => esc_html__( 'Warning!!! This will reset all the settings. Refresh the page after Save and Publish to reset all.', 'blogoholic' ),
	);

	wp_localize_script( 'blogoholic-customize-control', 'localized_data', $localized_data );
}
add_action( 'customize_controls_enqueue_scripts', 'blogoholic_customize_control_js' );
