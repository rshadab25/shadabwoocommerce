<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 *
 * blog section
 */

$wp_customize->add_section(
	'blogoholic_blog',
	array(
		'title' => esc_html__( 'Blog Post', 'blogoholic' ),
		'panel' => 'blogoholic_home_panel',
	)
);

$wp_customize->add_setting(
	'blogoholic_blog_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => esc_html__( 'More Articles', 'blogoholic' ),
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	'blogoholic_blog_title',
	array(
		'section'		=> 'blogoholic_blog',
		'label'			=> esc_html__( 'Section Title:', 'blogoholic' ),
		'active_callback' => 'blogoholic_if_blog_page'
	)
);

$wp_customize->selective_refresh->add_partial( 
	'blogoholic_blog_title', 
	array(
        'selector'            => '#inner-content-wrapper .section-title',
		'render_callback'     => 'blogoholic_blog_partial_title',
	) 
);

// blog enable settings
$wp_customize->add_setting(
	'blogoholic_blog',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'disable'
	)
);

$wp_customize->add_control(
	'blogoholic_blog',
	array(
		'section'		=> 'blogoholic_blog',
		'label'			=> esc_html__( 'Content type:', 'blogoholic' ),
		'description'			=> esc_html__( 'Choose where you want to render the content from.', 'blogoholic' ),
		'type'			=> 'select',
		'choices'		=> array( 
				'disable' => esc_html__( '--Disable--', 'blogoholic' ),
				'cat' => esc_html__( 'Category', 'blogoholic' ),
		 	)
	)
);
$wp_customize->add_setting(
	'blogoholic_blog_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => esc_html__( 'More Articles', 'blogoholic' ),
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	'blogoholic_blog_title',
	array(
		'section'		=> 'blogoholic_blog',
		'label'			=> esc_html__( 'Section Title:', 'blogoholic' ),
		'active_callback' => 'blogoholic_if_blog_enabled'
	)
);

$wp_customize->selective_refresh->add_partial( 
	'blogoholic_blog_title', 
	array(
        'selector'            => '#inner-content-wrapper .section-title',
		'render_callback'     => 'blogoholic_blog_partial_title',
	) 
);

// blog category setting
$wp_customize->add_setting(
	'blogoholic_blog_cat',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
	)
);

$wp_customize->add_control(
	'blogoholic_blog_cat',
	array(
		'section'		=> 'blogoholic_blog',
		'label'			=> esc_html__( 'Category:', 'blogoholic' ),
		'active_callback' => 'blogoholic_if_blog_cat',
		'type'			=> 'select',
		'choices'		=> blogoholic_get_post_cat_choices(),
	)
);

