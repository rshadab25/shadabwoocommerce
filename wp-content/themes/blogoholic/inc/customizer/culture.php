<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 *
 * culture section
 */

$wp_customize->add_section(
	'blogoholic_culture',
	array(
		'title' => esc_html__( 'Art & Culture', 'blogoholic' ),
		'panel' => 'blogoholic_home_panel',
	)
);

// culture enable settings
$wp_customize->add_setting(
	'blogoholic_culture',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'disable'
	)
);

$wp_customize->add_control(
	'blogoholic_culture',
	array(
		'section'		=> 'blogoholic_culture',
		'label'			=> esc_html__( 'Content type:', 'blogoholic' ),
		'description'			=> esc_html__( 'Choose where you want to render the content from.', 'blogoholic' ),
		'type'			=> 'select',
		'choices'		=> array( 
				'disable' => esc_html__( '--Disable--', 'blogoholic' ),
				'page' => esc_html__( 'Page', 'blogoholic' ),
		 	)
	)
);
$wp_customize->add_setting(
	'blogoholic_culture_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => esc_html__( 'Art & Culture', 'blogoholic' ),
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	'blogoholic_culture_title',
	array(
		'section'		=> 'blogoholic_culture',
		'label'			=> esc_html__( 'Title:', 'blogoholic' ),
		'active_callback'	=> 'blogoholic_if_culture_enabled',
	)
);

$wp_customize->selective_refresh->add_partial( 
	'blogoholic_culture_title', 
	array(
        'selector'            => '#art .section-title',
		'render_callback'     => 'blogoholic_culture_partial_title',
	) 
);

for ($i=1; $i <= 6 ; $i++) { 

	// culture page setting
	$wp_customize->add_setting(
		'blogoholic_culture_page_'.$i,
		array(
			'sanitize_callback' => 'blogoholic_sanitize_dropdown_pages',
			'default' => 0,
		)
	);

	$wp_customize->add_control(
		'blogoholic_culture_page_'.$i,
		array(
			'section'		=> 'blogoholic_culture',
			'label'			=> esc_html__( 'Page ', 'blogoholic' ).$i,
			'type'			=> 'dropdown-pages',
			'active_callback' => 'blogoholic_if_culture_page'
		)
	);
}

