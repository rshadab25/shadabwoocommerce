<?php

defined( 'ABSPATH' ) || exit;

return array(
	'weight'   => -1,
	'name'     => __( 'Icon with text', 'the7mk2' ),
	'base'     => 'dt_icon_text',
	'icon'     => 'dt_vc_text_with_icon',
	'class'    => 'dt_vc_icon',
	'category' => __( 'by Dream-Theme', 'the7mk2' ),
	'params'   => array(
		array(
			'heading'    => __( 'Layout', 'dt-the7-core' ),
			'param_name' => 'layout',
			'type'       => 'dt_radio_image',
			'value'      => 'layout_5',
			'options'    => array(
				'layout_5' => array(
					'title' => _x( 'Layout 1', 'dt-the7-core' ),
					'src'   => '/inc/shortcodes/images/l-01.gif',
				),
				'layout_4' => array(
					'title' => _x( 'Layout 2', 'dt-the7-core' ),
					'src'   => '/inc/shortcodes/images/l-02.gif',
				),
				'layout_3' => array(
					'title' => _x( 'Layout 3', 'dt-the7-core' ),
					'src'   => '/inc/shortcodes/images/l-03.gif',
				),
				'layout_1' => array(
					'title' => _x( 'Layout 4', 'dt-the7-core' ),
					'src'   => '/inc/shortcodes/images/l-04.gif',
				),
				'layout_2' => array(
					'title' => _x( 'Layout 5', 'dt-the7-core' ),
					'src'   => '/inc/shortcodes/images/l-05.gif',
				),
			),
		),
		array(
			'heading'    => __( 'Enable link', 'the7mk2' ),
			'param_name' => 'show_link',
			'type'       => 'dt_switch',
			'value'      => 'n',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
		),
		array(
			'type'        => 'vc_link',
			'class'       => '',
			'heading'     => __( 'Link ', 'the7mk2' ),
			'param_name'  => 'link',
			'value'       => '',
			'description' => __( 'for #anchor navigation', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'show_link',
				'value'   => 'y',
			),
		),
		array(
			'type'             => 'dropdown',
			'heading'          => __( 'Apply link to:', 'the7mk2' ),
			'param_name'       => 'apply_link',
			'value'            => array(
				__( 'Icon', 'the7mk2' )                  => 'icon',
				__( 'Title', 'the7mk2' )                 => 'title',
				__( 'Button', 'the7mk2' )                => 'button',
				__( 'Title + button', 'the7mk2' )        => 'title_button',
				__( 'Icon + title + button', 'the7mk2' ) => 'icon_title_button',
				__( 'Icon + button', 'the7mk2' )         => 'icon_button',
				__( 'Entire block', 'the7mk2' )          => 'block',
			),
			'dependency'       => array(
				'element' => 'show_link',
				'value'   => 'y',
			),
			'edit_field_class' => 'vc_col-sm-4',
		),
		array(
			'heading'     => __( 'Enable smooth scroll for anchor navigation', 'the7mk2' ),
			'param_name'  => 'smooth_scroll',
			'type'        => 'dt_switch',
			'value'       => 'n',
			'options'     => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency'  => array(
				'element' => 'show_link',
				'value'   => 'y',
			),
			'description' => __( 'for #anchor navigation', 'the7mk2' ),
		),
		array(
			'heading'		=> __( 'Extra class name', 'the7mk2' ),
			'description'	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'the7mk2' ),
			'param_name'	=> 'el_class',
			'type'			=> 'textfield',
		),
		// - Title.
		array(
			'heading'    => __( 'Title', 'the7mk2' ),
			'param_name' => 'dt_title',
			'type'       => 'dt_title',
			'value'      => '',
			'group'      => __( 'Content', 'the7mk2' ),
		),
		array(
			'group'            => __( 'Content', 'the7mk2' ),
			'type'             => 'textfield',
			'class'            => '',
			'heading'          => __( 'Title ', 'the7mk2' ),
			'param_name'       => 'dt_text_title',
			'value'            => 'Your title goes here',
			'edit_field_class' => 'vc_col-sm-8',
			'admin_label'      => true,
		),
		array(
			'group'            => __( 'Content', 'the7mk2' ),
			'type'             => 'dropdown',
			'heading'          => __( 'Tag', 'the7mk2' ),
			'param_name'       => 'heading_tag',
			'value'            => array(
				__( 'Default H4', 'the7mk2' ) => 'h4',
				__( 'H1', 'the7mk2' )         => 'h1',
				__( 'H2', 'the7mk2' )         => 'h2',
				__( 'H3', 'the7mk2' )         => 'h3',
				__( 'H5', 'the7mk2' )         => 'h5',
				__( 'H6', 'the7mk2' )         => 'h6',
				__( 'div', 'the7mk2' )        => 'div',
				__( 'p', 'the7mk2' )          => 'p',
				__( 'span', 'the7mk2' )       => 'span',
			),
			'description'      => __( 'Default is Div', 'the7mk2' ),
			'edit_field_class' => 'vc_col-sm-4',
		),
		array(
			'group'      => __( 'Content', 'the7mk2' ),
			'heading'    => __( 'Font style', 'the7mk2' ),
			'param_name' => 'dt_text_title_font_style',
			'type'       => 'dt_font_style',
			'value'      => ':bold:',
		),
		array(
			'group'            => __( 'Content', 'the7mk2' ),
			'heading'          => __( 'Font size', 'the7mk2' ),
			'param_name'       => 'dt_text_title_font_size',
			'type'             => 'dt_number',
			'value'            => '',
			'units'            => 'px',
			'edit_field_class' => 'vc_col-sm-3 vc_column',
			'description'      => __( 'Leave empty to use H4 font size.', 'the7mk2' ),
		),
		array(
			'group'            => __( 'Content', 'the7mk2' ),
			'heading'          => __( 'Line height', 'the7mk2' ),
			'param_name'       => 'dt_text_title_line_height',
			'type'             => 'dt_number',
			'value'            => '',
			'units'            => 'px',
			'edit_field_class' => 'vc_col-sm-3 vc_column',
			'description'      => __( 'Leave empty to use H4 line height.', 'the7mk2' ),
		),
		array(
			'group'       => __( 'Content', 'the7mk2' ),
			'heading'     => __( 'Font color', 'the7mk2' ),
			'param_name'  => 'dt_text_custom_title_color',
			'type'        => 'colorpicker',
			'value'       => '',
			'description' => __( 'Leave empty to use headings color.', 'the7mk2' ),
		),
		array(
			'group'      => __( 'Content', 'the7mk2' ),
			'heading'    => __( 'Gap below title', 'the7mk2' ),
			'param_name' => 'dt_text_title_bottom_margin',
			'type'       => 'dt_number',
			'value'      => '0px',
			'units'      => 'px',
		),
		// - Text.
		array(
			'heading'    => __( 'Text', 'the7mk2' ),
			'param_name' => 'dt_title',
			'type'       => 'dt_title',
			'value'      => '',
			'group'      => __( 'Content', 'the7mk2' ),
		),
		array(
			'group'      => __( 'Content', 'the7mk2' ),
			'type'       => 'textarea',
			'class'      => '',
			'heading'    => __( 'Text', 'the7mk2' ),
			'param_name' => 'dt_text_desc',
			'value'      => 'Your text goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
		),
		array(
			'heading'    => __( 'Font style', 'the7mk2' ),
			'param_name' => 'dt_text_content_font_style',
			'type'       => 'dt_font_style',
			'value'      => '',
			'dependency' => array(
				'element' => 'post_content',
				'value'   => array( 'show_excerpt', 'show_content' ),
			),
			'group'      => __( 'Content', 'the7mk2' ),
		),
		array(
			'heading'          => __( 'Font size', 'the7mk2' ),
			'param_name'       => 'dt_text_content_font_size',
			'type'             => 'dt_number',
			'value'            => '',
			'units'            => 'px',
			'edit_field_class' => 'vc_col-sm-3 vc_column',
			'dependency'       => array(
				'element' => 'post_content',
				'value'   => array( 'show_excerpt', 'show_content' ),
			),
			'description'      => __( 'Leave empty to use medium font size.', 'the7mk2' ),
			'group'            => __( 'Content', 'the7mk2' ),
		),
		array(
			'heading'          => __( 'Line height', 'the7mk2' ),
			'param_name'       => 'dt_text_content_line_height',
			'type'             => 'dt_number',
			'value'            => '',
			'units'            => 'px',
			'edit_field_class' => 'vc_col-sm-3 vc_column',
			'dependency'       => array(
				'element' => 'post_content',
				'value'   => array( 'show_excerpt', 'show_content' ),
			),
			'description'      => __( 'Leave empty to use medium line height.', 'the7mk2' ),
			'group'            => __( 'Content', 'the7mk2' ),
		),
		array(
			'heading'     => __( 'Font color', 'the7mk2' ),
			'param_name'  => 'dt_text_custom_content_color',
			'type'        => 'colorpicker',
			'value'       => '',
			'dependency'  => array(
				'element' => 'post_content',
				'value'   => array( 'show_excerpt', 'show_content' ),
			),
			'description' => __( 'Leave empty to use primary text color.', 'the7mk2' ),
			'group'       => __( 'Content', 'the7mk2' ),
		),
		array(
			'heading'          => __( 'Gap below text', 'the7mk2' ),
			'param_name'       => 'dt_text_content_bottom_margin',
			'type'             => 'dt_number',
			'value'            => '0px',
			'units'            => 'px',
			'edit_field_class' => 'vc_col-xs-12 vc_column dt_row-6',
			'dependency'       => array(
				'element' => 'post_content',
				'value'   => array( 'show_excerpt', 'show_content' ),
			),
			'group'            => __( 'Content', 'the7mk2' ),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Show button', 'the7mk2' ),
			'param_name' => 'show_btn',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'type'       => 'textfield',
			'class'      => '',
			'heading'    => __( 'Button text', 'the7mk2' ),
			'param_name' => 'button_text',
			'value'      => 'Button name',
			'dependency' => array(
				'element' => 'show_btn',
				'value'   => 'y',
			),
		),
		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'heading'     => __( 'Button appearance', 'the7mk2' ),
			'param_name'  => 'btn_size',
			'type'        => 'dropdown',
			'value'       => array(
				'Small'  => 'small',
				'Medium' => 'medium',
				'Large'  => 'big',
				'Custom' => 'custom',
				'Link' => 'link',
			),
			'dependency'  => array(
				'element' => 'show_btn',
				'value'   => 'y',
			),
			'description' => __( 'Small, medium & large buttons be set up in Theme Options / Buttons.', 'the7mk2' ),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Button width', 'the7mk2' ),
			'param_name' => 'btn_width',
			'type'       => 'dropdown',
			'value'      => array(
				'Default'   => 'btn_auto_width',
				'Custom'    => 'btn_fixed_width',
				'Fullwidth' => 'btn_full_width',
			),
			'dependency' => array(
				'element' => 'show_btn',
				'value'   => 'y',
			),
		),
		array(
			'group'            => __( 'Button', 'the7mk2' ),
			'heading'          => __( 'Width', 'the7mk2' ),
			'param_name'       => 'custom_btn_width',
			'type'             => 'dt_number',
			'value'            => '200px',
			'dependency'       => array(
				'element' => 'btn_width',
				'value'   => 'btn_fixed_width',
			),
			'edit_field_class' => 'vc_col-sm-3 vc_column dt_col_custom',
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Animation', 'the7mk2' ),
			'param_name' => 'btn_animation',
			'type'       => 'dropdown',
			'value'      => presscore_get_vc_animation_options(),

			'dependency' => array(
				'element' => 'show_btn',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Icon settings', 'the7mk2' ),
			'param_name' => 'dt_title_btn',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'show_btn',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Icon selector', 'the7mk2' ),
			'param_name' => 'icon_type',
			'type'       => 'dropdown',
			'std'        => 'none',
			'value'      => array(
				'No icon'     => 'none',
				'Plain HTML'  => 'html',
				'Icon picker' => 'picker',
			),
			'dependency' => array(
				'element' => 'show_btn',
				'value'   => 'y',
			),
		),
		array(
			'group'            => __( 'Button', 'the7mk2' ),
			'heading'          => __( 'Icon', 'the7mk2' ),
			'param_name'       => 'icon',
			'type'             => 'textarea_raw_html',
			'value'            => '',
			'description'      => 'f.e. <code>&lt;i class="fa fa-arrow-circle-right"&gt;&lt;/i&gt;</code> <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>.',
			'edit_field_class' => 'custom-textarea-height vc_col-xs-12  vc_column',
			'dependency'       => array(
				'element' => 'icon_type',
				'value'   => 'html',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Icon', 'the7mk2' ),
			'param_name' => 'btn_icon_picker',
			'type'       => 'dt_navigation',
			'value'      => '',
			'dependency' => array(
				'element' => 'icon_type',
				'value'   => 'picker',
			),
		),
		array(
			'heading'    => __( 'Icon gap', 'the7mk2' ),
			'param_name' => 'icon_gap',
			'type'       => 'dt_number',
			'value'      => '8px',
			'units'      => 'px',
			'group'      => __( 'Button', 'the7mk2' ),
			
			'dependency' => array(
				'element' => 'icon_type',
				'value'   => array( 'picker', 'html' ),
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Icon alignment', 'the7mk2' ),
			'param_name' => 'icon_align',
			'type'       => 'dropdown',
			'value'      => array(
				'Left'  => 'left',
				'Right' => 'right',
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value'   => array( 'picker', 'html' ),
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'General settings', 'the7mk2' ),
			'param_name' => 'dt_title_btn',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'type'       => 'dt_number',
			'heading'    => __( 'Font size', 'the7mk2' ),
			'param_name' => 'font_size',
			'value'      => '14px',
			'units'      => 'px',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Icon size', 'the7mk2' ),
			'param_name' => 'icon_size',
			'type'       => 'dt_number',
			'value'      => '11px',
			'units'      => 'px',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Padding', 'the7mk2' ),
			'param_name' => 'button_padding',
			'type'       => 'dt_spacing',
			'value'      => '12px 18px 12px 18px',
			'units'      => 'px',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'type'       => 'dt_number',
			'heading'    => __( 'Border width', 'the7mk2' ),
			'param_name' => 'border_width',
			'value'      => '0px',
			'units'      => 'px',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'type'       => 'dt_number',
			'heading'    => __( 'Border radius', 'the7mk2' ),
			'param_name' => 'border_radius',
			'value'      => '1px',
			'units'      => 'px',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'type'       => 'dropdown',
			'class'      => '',
			'heading'    => __( 'Decoration', 'the7mk2' ),
			'param_name' => 'btn_decoration',
			'value'      => array(
				'None'   => 'none',
				'3D'     => 'btn_3d',
				'Shadow' => 'btn_shadow',
			),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Normal', 'the7mk2' ),
			'param_name' => 'dt_title_btn',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'type'        => 'colorpicker',
			'class'       => '',
			'heading'     => __( 'Text and icon color', 'the7mk2' ),
			'param_name'  => 'text_color',
			'value'       => '',
			'description' => __( 'Leave empty to use default color from Theme Options/Buttons ', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'heading'    => __( 'Border color', 'the7mk2' ),
			'param_name' => 'default_btn_border',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
			'group'      => __( 'Button', 'the7mk2' ),
		),
		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'type'        => 'colorpicker',
			'class'       => '',
			'param_name'  => 'default_btn_border_color',
			'value'       => '',
			'description' => __( 'Leave empty to use default color from Theme Options/Buttons ', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'default_btn_border',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Background color', 'the7mk2' ),
			'param_name' => 'default_btn_bg',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'type'        => 'colorpicker',
			'class'       => '',
			'param_name'  => 'default_btn_bg_color',
			'value'       => '',
			'description' => __( 'Leave empty to use default color from Theme Options/Buttons ', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'default_btn_bg',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Hover', 'the7mk2' ),
			'param_name' => 'dt_title_btn',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Enable hover', 'the7mk2' ),
			'param_name' => 'default_btn_hover',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'custom',
			),
		),
		/**
		 * Link settings.
		 */
		array(
			'heading'    => __( 'General settings', 'the7mk2' ),
			'param_name' => 'link_general_settings',
			'type'       => 'dt_title',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'    => __( 'Font size', 'the7mk2' ),
			'param_name' => 'link_font_size',
			'type'       => 'dt_number',
			'value'      => '14px',
			'units'      => 'px',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Font style', 'the7mk2' ),
			'param_name' => 'link_font_style',
			'type'       => 'dt_font_style',
			'value'      => ':bold:',
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'    => __( 'Icon size', 'the7mk2' ),
			'param_name' => 'link_icon_size',
			'type'       => 'dt_number',
			'value'      => '12px',
			'units'      => 'px',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'    => __( 'Padding', 'the7mk2' ),
			'param_name' => 'link_padding',
			'type'       => 'dt_spacing',
			'value'      => '4px 0px 4px 0px',
			'units'      => 'px',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		
		array(
			'heading'    => __( 'Normal', 'the7mk2' ),
			'param_name' => 'link_normal_settings',
			'type'       => 'dt_title',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'     => __( 'Text and icon color', 'the7mk2' ),
			'param_name'  => 'link_text_color',
			'type'        => 'colorpicker',
			'value'       => '',
			'description' => __( 'Live empty to use accent color. ', 'the7mk2' ),
			'group'       => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'    => __( 'Hover', 'the7mk2' ),
			'param_name' => 'link_hover_settings',
			'type'       => 'dt_title',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'    => __( 'Enable hover', 'the7mk2' ),
			'param_name' => 'link_hover',
			'type'       => 'dt_switch',
			'value'      => 'n',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),
		array(
			'heading'     => __( 'Text and icon color', 'the7mk2' ),
			'param_name'  => 'link_text_hover_color',
			'type'        => 'colorpicker',
			'value'       => '',
			'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'link_hover',
				'value'   => 'y',
			),
			'group'       => __( 'Button', 'the7mk2' ),
		),
		array(
			'heading'    => __( 'Underline', 'the7mk2' ),
			'param_name' => 'link_underline_settings',
			'type'       => 'dt_title',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
		),

		array(
			'heading'    => __( 'Decoration', 'the7mk2' ),
			'param_name' => 'link_decoration',
			'type'       => 'dropdown',
			'std'        => 'upwards',
			'value'      => array(
				'None'   => 'none',
				'Left to right' => 'left_to_right',
				'From center' => 'from_center' ,
				'Upwards' => 'upwards',
				'Downwards' => 'downwards',
			),
			'dependency' => array(
				'element' => 'btn_size',
				'value'   => 'link',
			),
			'group'      => __( 'Button', 'the7mk2' ),
		),
		array(
			'heading'    => __( 'Line width', 'the7mk2' ),
			'param_name' => 'link_border_width',
			'type'       => 'dt_number',
			'value'      => '2px',
			'units'      => 'px',
			'group'      => __( 'Button', 'the7mk2' ),
			'dependency' => array(
				'element' => 'link_decoration',
				'value'   => array( 'left_to_right', 'from_center', 'upwards', 'downwards' ),
			),
		),

		array(
			'heading'    => __( 'Line color', 'the7mk2' ),
			'param_name'  => 'link_border_color',
			'type'        => 'colorpicker',
			'value'       => '',
			'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
			'dependency' => array(
				'element' => 'link_decoration',
				'value'   => array( 'left_to_right', 'from_center', 'upwards', 'downwards' ),
			),
			'group'       => __( 'Button', 'the7mk2' ),
		),


		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'type'        => 'colorpicker',
			'class'       => '',
			'heading'     => __( 'Text and icon color', 'the7mk2' ),
			'param_name'  => 'text_hover_color',
			'value'       => '',
			'description' => __( 'Leave empty to use default color from Theme Options/Buttons ', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'default_btn_hover',
				'value'   => 'y',
			),
		),
		array(
			'heading'    => __( 'Border color', 'the7mk2' ),
			'param_name' => 'default_btn_border_hover',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'default_btn_hover',
				'value'   => 'y',
			),
			'group'      => __( 'Button', 'the7mk2' ),
		),
		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'type'        => 'colorpicker',
			'class'       => '',
			'param_name'  => 'default_btn_border_hover_color',
			'value'       => '',
			'description' => __( 'Leave empty to use default color from Theme Options/Buttons ', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'default_btn_border_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Button', 'the7mk2' ),
			'heading'    => __( 'Background color', 'the7mk2' ),
			'param_name' => 'default_btn_bg_hover',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'default_btn_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'       => __( 'Button', 'the7mk2' ),
			'type'        => 'colorpicker',
			'class'       => '',
			'param_name'  => 'bg_hover_color',
			'value'       => '',
			'description' => __( 'Leave empty to use default color from Theme Options/Buttons ', 'the7mk2' ),
			'dependency'  => array(
				'element' => 'default_btn_bg_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Show icon', 'the7mk2' ),
			'param_name' => 'show_icon',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),

		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Icon', 'the7mk2' ),
			'param_name' => 'dt_title_icon',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Choose icon', 'the7mk2' ),
			'param_name' => 'icon_picker',
			'type'       => 'dt_navigation',
			'value'      => 'icomoon-the7-font-the7-mail-01',
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),

		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Icon size', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_size',
			'type'             => 'dt_number',
			'value'            => '32px',
			'units'            => 'px',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Icon Background', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_title',
			'type'             => 'dt_title_icon',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Background size', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_bg_size',
			'type'             => 'dt_number',
			'value'            => '60px',
			'units'            => 'px',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Border radius', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_border_radius',
			'type'             => 'dt_number',
			'value'            => '200px',
			'units'            => 'px',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Border width', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_border_width',
			'type'             => 'dt_number',
			'value'            => '2',
			'units'            => 'px',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Border style', 'the7mk2' ),
			'param_name'       => 'icon_border_style',
			'type'             => 'dropdown',
			'std'              => 'solid',
			'value'            => array(
				'Solid'  => 'solid',
				'Dotted' => 'dotted',
				'Dashed' => 'dashed',
				'Double' => 'double',
			),
			'edit_field_class' => 'vc_col-xs-12 vc_column dt_row-6',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Icon gaps', 'the7mk2' ),
			'param_name' => 'dt_text_icon_paddings',
			'type'       => 'dt_spacing',
			'value'      => '0px 0px 0px 0px',
			'units'      => 'px',
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Normal', 'the7mk2' ),
			'param_name' => 'dt_title_icon',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Icon color', 'the7mk2' ),
			'description'      => __( 'Live empty to use accent color.', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_color',
			'type'             => 'colorpicker',
			'value'            => 'rgba(255,255,255,1)',
			'dependency'       => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Show icon border color', 'the7mk2' ),
			'param_name' => 'dt_icon_border',
			'type'       => 'dt_switch',
			'value'      => 'n',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Border color', 'the7mk2' ),
			'description'      => __( 'Live empty to use accent color.', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_border_color',
			'type'             => 'colorpicker',
			'value'            => '',
			'dependency'       => array(
				'element' => 'dt_icon_border',
				'value'   => 'y',
			),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Show icon background', 'the7mk2' ),
			'param_name' => 'dt_icon_bg',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Background color', 'the7mk2' ),
			'param_name'       => 'dt_text_icon_bg_color',
			'type'             => 'colorpicker',
			'value'            => '',
			'dependency'       => array(
				'element' => 'dt_icon_bg',
				'value'   => 'y',
			),
			'description'      => __( 'Live empty to use accent color.', 'the7mk2' ),
			'edit_field_class' => 'the7-icons-dependent vc_col-xs-12',
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Hover', 'the7mk2' ),
			'param_name' => 'dt_title_icon',
			'type'       => 'dt_title',
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Enable hover', 'the7mk2' ),
			'param_name' => 'dt_icon_hover',
			'type'       => 'dt_switch',
			'value'      => 'n',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'show_icon',
				'value'   => 'y',
			),
		),
		array(
			'group'       => __( 'Icon', 'the7mk2' ),
			'heading'     => __( 'Icon color', 'the7mk2' ),
			'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
			'param_name'  => 'dt_icon_color_hover',
			'type'        => 'colorpicker',
			'value'       => 'rgba(255,255,255,1)',
			'dependency'  => array(
				'element' => 'dt_icon_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Show icon border color', 'the7mk2' ),
			'param_name' => 'dt_icon_border_hover',
			'type'       => 'dt_switch',
			'value'      => 'n',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'dt_icon_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'       => __( 'Icon', 'the7mk2' ),
			'heading'     => __( 'Icon border color  ', 'the7mk2' ),
			'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
			'param_name'  => 'dt_icon_border_color_hover',
			'type'        => 'colorpicker',
			'value'       => '',
			'dependency'  => array(
				'element' => 'dt_icon_border_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'      => __( 'Icon', 'the7mk2' ),
			'heading'    => __( 'Show icon background', 'the7mk2' ),
			'param_name' => 'dt_icon_bg_hover',
			'type'       => 'dt_switch',
			'value'      => 'y',
			'options'    => array(
				'Yes' => 'y',
				'No'  => 'n',
			),
			'dependency' => array(
				'element' => 'dt_icon_hover',
				'value'   => 'y',
			),
		),
		array(
			'group'       => __( 'Icon', 'the7mk2' ),
			'heading'     => __( 'Icon background color', 'the7mk2' ),
			'param_name'  => 'dt_icon_bg_color_hover',
			'type'        => 'colorpicker',
			'value'       => '',
			'dependency'  => array(
				'element' => 'dt_icon_bg_hover',
				'value'   => 'y',
			),
			'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
		),
		array(
			'group'            => __( 'Icon', 'the7mk2' ),
			'heading'          => __( 'Animation', 'the7mk2' ),
			'param_name'       => 'icon_animation',
			'type'             => 'dropdown',
			'std'              => 'none',
			'value'            => array(
				'None'        => 'none',
				'Slide up'    => 'slide_up',
				'Slide right' => 'slide_right',
				'Spin around' => 'spin_around',
				'Shadow'      => 'shadow',
				'Scale up'    => 'scale',
				'Scale down'  => 'scale_down',
			),
			'edit_field_class' => 'vc_col-xs-12 vc_column dt_row-6',
			'dependency'       => array(
				'element' => 'dt_icon_hover',
				'value'   => 'y',
			),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'the7mk2' ),
			'param_name' => 'css_dt_carousel',
			'group'      => __( 'Design Options', 'the7mk2' ),
		),
	),
);

