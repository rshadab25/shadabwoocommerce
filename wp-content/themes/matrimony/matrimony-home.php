<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Template Name: Home Template
 * @package Matrimony
 */
get_header(); ?>
	<div id="primary" class="content-area">
	        <main id="main" class="site-main" role="main">
		        <?php
		        	// Portfolio Section
		        	get_template_part( 'template-parts/section/section', 'portfolio' );
		        	// Counter Section
		        	get_template_part( 'template-parts/section/section', 'counter' );
		        	// History Section
		        	get_template_part( 'template-parts/section/section', 'history' );
		        	// Service section 
		        	get_template_part( 'template-parts/section/section', 'service' );
		        	//Gallery
		        	get_template_part( 'template-parts/section/section', 'gallery' );
		        	// Reservation
		        	get_template_part( 'template-parts/section/section', 'reservation' );
		        	// Testimonial 
		        	get_template_part( 'template-parts/section/section', 'testimonial' );
		        	// Gift Section
		        	get_template_part( 'template-parts/section/section', 'gift' );
		        	// Blog
		        	get_template_part( 'template-parts/section/section', 'blog' );
		        	// Client 
		        	get_template_part( 'template-parts/section/section', 'client' );
		      	?>
	        </main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer();?>