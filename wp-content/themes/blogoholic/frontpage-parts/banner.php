<?php
/**
 * Template part for displaying front page imtroduction.
 *
 * @package Moral
 */


// Get the content type.
$banner = get_theme_mod( 'blogoholic_banner', 'disable' );
$banner_num	= 5 ;
// Bail if the section is disabled.
if ( 'disable' === $banner ) {
	return;
}

$content_id = array();
for ( $i=1; $i <= $banner_num; $i++ ) { 
    $content_id[] = get_theme_mod( "blogoholic_banner_{$banner}_" . $i );
}

$args = array(
    'post_type' => $banner,
    'post__in' => (array)$content_id,   
    'orderby'   => 'post__in',
    'posts_per_page' => absint( $banner_num ),
    'ignore_sticky_posts' => true,
);

?>
<div id="posts-banner" class="relative">
    <div class="wrapper">
        <div class="grid">
        <?php
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
        	while ( $query->have_posts() ) {
        		$query->the_post();
        ?>
			<article class="grid-item">
                <div class="featured-image" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url( ) ); ?>);">
                    <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
                </div>
                <div class="entry-container">
                	<span class="cat-links">
                		<?php the_category( '', '' ) ?>
                	</span>
                    
                    <header class="entry-header">
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    </header>
                    <div class="entry-meta">
                        <?php blogoholic_posted_on() ; ?>
                    </div><!-- .entry-meta -->
                </div><!-- .entry-container -->
            </article>

        <?php	}
        	wp_reset_postdata();
        } ?>
		</div><!-- .grid-item -->
    </div><!-- .wrapper -->
</div>
