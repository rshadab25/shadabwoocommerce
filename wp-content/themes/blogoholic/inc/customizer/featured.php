<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 *
 * Featured section
 */
$wp_customize->add_section(
	'blogoholic_featured',
	array(
		'title' => esc_html__( 'Featured Post', 'blogoholic' ),
		'panel' => 'blogoholic_home_panel',
	)
);

// featured enable settings
$wp_customize->add_setting(
	'blogoholic_featured',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'disable'
	)
);

$wp_customize->add_control(
	'blogoholic_featured',
	array(
		'section'		=> 'blogoholic_featured',
		'label'			=> esc_html__( 'Content type:', 'blogoholic' ),
		'description'			=> esc_html__( 'Choose where you want to render the content from.', 'blogoholic' ),
		'type'			=> 'select',
		'choices'		=> array( 
				'disable' => esc_html__( '--Disable--', 'blogoholic' ),
				'page' => esc_html__( 'Page', 'blogoholic' ),
		 	)
	)
);



// featured page setting
$wp_customize->add_setting(
	'blogoholic_featured_page',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_dropdown_pages',
		'default' => 0,
	)
);

$wp_customize->add_control(
	'blogoholic_featured_page',
	array(
		'section'		=> 'blogoholic_featured',
		'label'			=> esc_html__( 'Page ', 'blogoholic' ),
		'type'			=> 'dropdown-pages',
		'active_callback' => 'blogoholic_if_featured_page'
	)
);
