<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Matrimony
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
<script type="text/javascript">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script></head>
<body  <?php body_class(); ?>>
	<?php if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else{
		do_action( 'wp_body_open' );
	} ?>	
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'matrimony' ); ?></a>
		<?php if( is_front_page() ) {
	    	do_action('matrimony_top_header_callback_action');
		} ?>
		<header id="masthead" class="site-header">
			<?php do_action('matrimony_header_hook_callback_action'); ?>
		</header><!-- #masthead -->
		<!-- Page breadcrumbs -->
		<?php $brd_option = get_theme_mod('general_header_section_options',0);
		if ( is_home() || !is_front_page() ){
			$banner_title = apply_filters( 'matrimony_filter_banner_title', '' );
			if($brd_option == '1'):	?>
				<section class="page-title-wrap" style="background-image: url('<?php echo esc_url(get_theme_mod('matrimony_breadcrumb_image','')); ?>') ";>
				    <div class="page-title">
				        <h3 class="entry-title"><?php echo esc_html( $banner_title ); ?></h3>
				    </div>
				</section><!-- featured-slider ends here -->
			<?php endif; 
		}?>
		<div id="content" class="site-content">
