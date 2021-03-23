<?php
/**
 * Template part for displaying front page imtroduction.
 *
 * @package Moral
 */

// Get the content type.
$featured = get_theme_mod( 'blogoholic_featured', 'disable' );

// Bail if the section is disabled.
if ( 'disable' === $featured ) {
	return;
}

$content_id = get_theme_mod( "blogoholic_featured_{$featured}");

$args = array(
    'post_type' => $featured,
    'post__in' => (array)$content_id,   
);
?>
<div id="featured-posts" class="page-section no-padding-top">
    <div class="wrapper">
    	<?php
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
		?>
        <article>
            <div class="featured-image" style="background-image: url('<?php the_post_thumbnail_url() ; ?>');">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
            </div>
            <div class="entry-container">
                <div class="entry-meta">
                    <span class="cat-links">
                        <?php the_category( '', '' ) ?>
                    </span><!-- .cat-links -->
                </div>

                <header class="entry-header">
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title() ; ?></a></h2>
                </header>

                <div class="entry-content">
                   <p><?php echo esc_html(wp_trim_words( get_the_content(), 50, '  ...' )); ?></p>
                </div>
            
                <div class="read-more">
                    <a href="<?php the_permalink() ; ?>" class="btn">
                    	<?php echo esc_html( get_theme_mod( 'blogoholic_featured_btn_title', __( 'Read More', 'blogoholic') ) ); ?>
                    </a>
                </div>
            </div><!-- .entry-container -->
        </article>
        <?php	} }	wp_reset_postdata(); ?>
    </div>
</div><!-- #featured-posts -->