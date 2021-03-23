<?php
/**
 * Moral Theme Customizer
 *
 * @package Moral
 *
 * lifestyle section
 */

$wp_customize->add_section(
	'blogoholic_lifestyle',
	array(
		'title' => esc_html__( 'Lifestyle', 'blogoholic' ),
		'panel' => 'blogoholic_home_panel',
	)
);

// lifestyle enable settings
$wp_customize->add_setting(
	'blogoholic_lifestyle',
	array(
		'sanitize_callback' => 'blogoholic_sanitize_select',
		'default' => 'disable'
	)
);

$wp_customize->add_control(
	'blogoholic_lifestyle',
	array(
		'section'		=> 'blogoholic_lifestyle',
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
	'blogoholic_lifestyle_title',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => esc_html__( 'lifestyle Posts', 'blogoholic' ),
		'transport'	=> 'postMessage',
	)
);

$wp_customize->add_control(
	'blogoholic_lifestyle_title',
	array(
		'section'		=> 'blogoholic_lifestyle',
		'label'			=> esc_html__( 'Title:', 'blogoholic' ),
		'active_callback'	=> 'blogoholic_if_lifestyle_enabled',
	)
);

$wp_customize->selective_refresh->add_partial( 
	'blogoholic_lifestyle_title', 
	array(
        'selector'            => '#lifestyle .section-title',
		'render_callback'     => 'blogoholic_lifestyle_partial_title',
	) 
);


for ($i=1; $i <= 3 ; $i++) { 
	// lifestyle page setting
	$wp_customize->add_setting(
		'blogoholic_lifestyle_page_'.$i,
		array(
			'sanitize_callback' => 'blogoholic_sanitize_dropdown_pages',
			'default' => 0,
		)
	);

	$wp_customize->add_control(
		'blogoholic_lifestyle_page_'.$i,
		array(
			'section'		=> 'blogoholic_lifestyle',
			'label'			=> esc_html__( 'Page ', 'blogoholic' ).$i,
			'type'			=> 'dropdown-pages',
			'active_callback' => 'blogoholic_if_lifestyle_page'
		)
	);
}
