<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 *
 * banner section
 */

$wp_customize->add_section(
	'blogoholic_banner',
	array(
		'title' => esc_html__( 'Posts Banner', 'blogoholic' ),
		'panel' => 'blogoholic_home_panel',
	)
);

// banner enable settings
$wp_customize->add_setting(
	'blogoholic_banner',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'disable'
	)
);

$wp_customize->add_control(
	'blogoholic_banner',
	array(
		'section'		=> 'blogoholic_banner',
		'label'			=> esc_html__( 'Content type:', 'blogoholic' ),
		'description'			=> esc_html__( 'Choose where you want to render the content from.', 'blogoholic' ),
		'type'			=> 'select',
		'choices'		=> array( 
				'disable' => esc_html__( '--Disable--', 'blogoholic' ),
				'page' => esc_html__( 'Page', 'blogoholic' ),
		 	)
	)
);


for ($i=1; $i <= 5 ; $i++) { 

	// banner page setting
	$wp_customize->add_setting(
		'blogoholic_banner_page_'.$i,
		array(
			'sanitize_callback' => 'blogoholic_sanitize_dropdown_pages',
			'default' => 0,
		)
	);

	$wp_customize->add_control(
		'blogoholic_banner_page_'.$i,
		array(
			'section'		=> 'blogoholic_banner',
			'label'			=> esc_html__( 'Page ', 'blogoholic' ).$i,
			'type'			=> 'dropdown-pages',
			'active_callback' => 'blogoholic_if_banner_page'
		)
	);
}
