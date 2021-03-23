<?php
/**
 * Moral Theme 
 *
 * @package Moral
 * active callbacks.
 * 
 */

/**
 * Check if the banner is enabled
 */
function blogoholic_if_banner_enabled( $control ) {
	return 'disable' != $control->manager->get_setting( 'blogoholic_banner' )->value();
}



/**
 * Check if the banner is page
 */
function blogoholic_if_banner_page( $control ) {
	return 'page' === $control->manager->get_setting( 'blogoholic_banner' )->value();
}






/**
 * Check if the lifestyle is enabled
 */
function blogoholic_if_lifestyle_enabled( $control ) {
	return 'disable' != $control->manager->get_setting( 'blogoholic_lifestyle' )->value();
}


/**
 * Check if the lifestyle is page
 */
function blogoholic_if_lifestyle_page( $control ) {
	return 'page' === $control->manager->get_setting( 'blogoholic_lifestyle' )->value();
}
	

/**
 * Check if the culture is enabled
 */
function blogoholic_if_culture_enabled( $control ) {
	return 'disable' != $control->manager->get_setting( 'blogoholic_culture' )->value();
}


/**
 * Check if the culture is page
 */
function blogoholic_if_culture_page( $control ) {
	return 'page' === $control->manager->get_setting( 'blogoholic_culture' )->value();
}





/**
 * Check if the featured is page
 */
function blogoholic_if_featured_page( $control ) {
	return 'page' === $control->manager->get_setting( 'blogoholic_featured' )->value();
}

/**
 * Check if the blog is enabled
 */
function blogoholic_if_blog_enabled( $control ) {
	return 'disable' != $control->manager->get_setting( 'blogoholic_blog' )->value();
}

/**
 * Check if the blog is custom
 */
function blogoholic_if_blog_cat( $control ) {
	return 'cat' === $control->manager->get_setting( 'blogoholic_blog' )->value();
}


