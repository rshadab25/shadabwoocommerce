<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 *
 * advance setting
 */

$wp_customize->add_panel(
	'blogoholic_general_panel',
	array(
		'title' => esc_html__( 'Theme Options', 'blogoholic' ),
		'priority' => 107
	)
);


// Header tagline color setting
$wp_customize->add_setting(	
	'blogoholic_header_tagline',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_hex_color',
		'default' => '#929292',
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'blogoholic_header_tagline',
		array(
			'section'		=> 'colors',
			'label'			=> esc_html__( 'Site tagline Color:', 'blogoholic' ),
		)
	)
);


/**
 * General settings
 */
// General settings
$wp_customize->add_section(
	'blogoholic_general_section',
	array(
		'title' => esc_html__( 'General', 'blogoholic' ),
		'panel' => 'blogoholic_general_panel',
	)
);

// Backtop enable setting
$wp_customize->add_setting(
	'blogoholic_back_to_top_enable',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_checkbox',
		'default' => true,
	)
);

$wp_customize->add_control(
	'blogoholic_back_to_top_enable',
	array(
		'section'		=> 'blogoholic_general_section',
		'label'			=> esc_html__( 'Enable Scroll up.', 'blogoholic' ),
		'type'			=> 'checkbox',
	)
);

/**
 * Global Layout
 */
// Global Layout
$wp_customize->add_section(
	'blogoholic_global_layout',
	array(
		'title' => esc_html__( 'Global Layout', 'blogoholic' ),
		'panel' => 'blogoholic_general_panel',
	)
);

// Global site layout setting
$wp_customize->add_setting(
	'blogoholic_site_layout',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'wide',
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	'blogoholic_site_layout',
	array(
		'section'		=> 'blogoholic_global_layout',
		'label'			=> esc_html__( 'Site layout', 'blogoholic' ),
		'type'			=> 'radio',
		'choices'		=> array( 
			'boxed' => esc_html__( 'Boxed', 'blogoholic' ), 
			'wide' => esc_html__( 'Wide', 'blogoholic' ), 
		),
	)
);

// Global archive layout setting
$wp_customize->add_setting(
	'blogoholic_archive_sidebar',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'right',
	)
);

$wp_customize->add_control(
	'blogoholic_archive_sidebar',
	array(
		'section'		=> 'blogoholic_global_layout',
		'label'			=> esc_html__( 'Archive Sidebar', 'blogoholic' ),
		'description'			=> esc_html__( 'This option works on all archive pages like: 404, search, date, category, "Your latest posts" and so on.', 'blogoholic' ),
		'type'			=> 'radio',
		'choices'		=> array(  
			'right' => esc_html__( 'Right', 'blogoholic' ), 
			'no' => esc_html__( 'No Sidebar', 'blogoholic' ), 
		),
	)
);

// Global page layout setting
$wp_customize->add_setting(
	'blogoholic_global_page_layout',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'right',
	)
);

$wp_customize->add_control(
	'blogoholic_global_page_layout',
	array(
		'section'		=> 'blogoholic_global_layout',
		'label'			=> esc_html__( 'Global page sidebar', 'blogoholic' ),
		'description'			=> esc_html__( 'This option works only on single pages including "Posts page". This setting can be overridden for single page from the metabox too.', 'blogoholic' ),
		'type'			=> 'radio',
		'choices'		=> array(  
			'right' => esc_html__( 'Right', 'blogoholic' ), 
			'no' => esc_html__( 'No Sidebar', 'blogoholic' ), 
		),
	)
);

// Global post layout setting
$wp_customize->add_setting(
	'blogoholic_global_post_layout',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'right',
	)
);

$wp_customize->add_control(
	'blogoholic_global_post_layout',
	array(
		'section'		=> 'blogoholic_global_layout',
		'label'			=> esc_html__( 'Global post sidebar', 'blogoholic' ),
		'description'			=> esc_html__( 'This option works only on single posts. This setting can be overridden for single post from the metabox too.', 'blogoholic' ),
		'type'			=> 'radio',
		'choices'		=> array(  
			'right' => esc_html__( 'Right', 'blogoholic' ), 
			'no' => esc_html__( 'No Sidebar', 'blogoholic' ), 
		),
	)
);

/**
 * Blog/Archive section 
 */
// Blog/Archive section 
$wp_customize->add_section(
	'blogoholic_archive_settings',
	array(
		'title' => esc_html__( 'Archive/Blog', 'blogoholic' ),
		'description' => esc_html__( 'Settings for archive pages including blog page too.', 'blogoholic' ),
		'panel' => 'blogoholic_general_panel',
	)
);

// Archive excerpt setting
$wp_customize->add_setting(
	'blogoholic_archive_excerpt',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => esc_html__( 'View the post', 'blogoholic' ),
	)
);

$wp_customize->add_control(
	'blogoholic_archive_excerpt',
	array(
		'section'		=> 'blogoholic_archive_settings',
		'label'			=> esc_html__( 'Excerpt more text:', 'blogoholic' ),
	)
);

// Archive excerpt length setting
$wp_customize->add_setting(
	'blogoholic_archive_excerpt_length',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_number_range',
		'default' => 60,
	)
);

$wp_customize->add_control(
	'blogoholic_archive_excerpt_length',
	array(
		'section'		=> 'blogoholic_archive_settings',
		'label'			=> esc_html__( 'Excerpt more length:', 'blogoholic' ),
		'type'			=> 'number',
		'input_attrs'   => array( 'min' => 5 ),
	)
);

/**
 * Reset all settings
 */
// Reset settings section
$wp_customize->add_section(
	'blogoholic_reset_sections',
	array(
		'title' => esc_html__( 'Reset all', 'blogoholic' ),
		'description' => esc_html__( 'Reset all settings to default.', 'blogoholic' ),
		'panel' => 'blogoholic_general_panel',
	)
);

// Reset sortable order setting
$wp_customize->add_setting(
	'blogoholic_reset_settings',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_checkbox',
		'default' => false,
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	'blogoholic_reset_settings',
	array(
		'section'		=> 'blogoholic_reset_sections',
		'label'			=> esc_html__( 'Reset all settings?', 'blogoholic' ),
		'type'			=> 'checkbox',
	)
);

