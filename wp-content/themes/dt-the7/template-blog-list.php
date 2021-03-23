<?php
/**
 * Template Name: Blog - list
 *
 * Blog list template.
 *
 * @since   1.0.0
 *
 * @package The7\Templates
 */

defined( 'ABSPATH' ) || exit;

$config = presscore_config();
$config->set( 'template', 'blog' );
$config->set( 'template.layout.type', 'list' );

add_action( 'presscore_before_main_container', 'presscore_page_content_controller', 15 );

get_header();

if ( presscore_is_content_visible() ) : ?>

	<!-- Content -->
	<div id="content" class="content" role="main">

		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();

				do_action( 'presscore_before_loop' );

				if ( post_password_required() ) {
					the_content();
				} else {
					$config_backup = $config->get();

					presscore_display_posts_filter(
						array(
							'post_type' => 'post',
							'taxonomy'  => 'category',
						)
					);

					$container_class = array( 'articles-list' );
					if ( $config->get( 'post.fancy_date.enabled' ) ) {
						$container_class[] = presscore_blog_fancy_date_class();
					}

					echo '<div ' . presscore_list_container_html_class(
						$container_class
					) . presscore_list_container_data_atts() . '>';

					$page_query = presscore_get_blog_query();

					if ( $page_query->have_posts() ) :
						while ( $page_query->have_posts() ) :
							$page_query->the_post();

							$config->set( 'post.query.var.current_post', $page_query->current_post + 1 );

							presscore_populate_post_config();

							presscore_get_template_part( 'theme', 'blog/list/blog-list-post' );

						endwhile;
						wp_reset_postdata();
					endif;

					echo '</div>';

					presscore_complex_pagination( $page_query );

					$config->reset( $config_backup );
				}

				do_action( 'presscore_after_loop' );

				the7_display_post_share_buttons( 'page' );

			endwhile;
		endif;
		?>

	</div><!-- #content -->

	<?php do_action( 'presscore_after_content' );

endif;

get_footer(); ?>
