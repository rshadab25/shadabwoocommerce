<?php
/**
 * Moral Theme 
 *
 * @package Moral
 * partial refresh
 * 
 */


/**
 * Selective refresh for lifestyle title.
 */
function blogoholic_lifestyle_partial_title() {
	return esc_html( get_theme_mod( 'blogoholic_lifestyle_title' ) );
}


/**
 * Selective refresh for culture title.
 */
function blogoholic_culture_partial_title() {
	return esc_html( get_theme_mod( 'blogoholic_culture_title' ) );
}

/**
 * Selective refresh for blog title.
 */
function blogoholic_blog_partial_title() {
	return esc_html( get_theme_mod( 'blogoholic_blog_title' ) );
}
