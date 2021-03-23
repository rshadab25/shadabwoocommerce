<?php
/**
 * Template part for displaying content  in post.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Moral
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry' ); ?>>
	<?php
	$blogoholic_enable_single_featured_img = get_theme_mod( 'blogoholic_enable_single_featured_img', true );
	if ( has_post_thumbnail() && $blogoholic_enable_single_featured_img ) : ?>
		<div class="featured-image">
	        <?php the_post_thumbnail( 'full', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
		</div><!-- .featured-post-image -->
	<?php endif; ?>

	<div class="entry-meta">
	    <?php
	    		blogoholic_posted_on();
	    ?>
	</div><!-- .entry-meta -->
	<div class="entry-container">
	    <div class="entry-content">
	        <?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'blogoholic' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blogoholic' ),
				'after'  => '</div>',
			) );
			?>
	    </div><!-- .entry-content -->
	    <?php
	    	blogoholic_post_author();
		?>
	</div><!-- .entry-container -->
    <div class="entry-meta">
    	<?php blogoholic_cats(); blogoholic_tags(); ?>
    </div><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->