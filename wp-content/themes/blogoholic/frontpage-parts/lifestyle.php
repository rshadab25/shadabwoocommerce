<?php
/**
 * Template part for displaying front page imtroduction.
 *
 * @package Moral
 */

// Get the content type.
$lifestyle = get_theme_mod( 'blogoholic_lifestyle', 'disable' );
$lifestyle_num	= get_theme_mod( 'blogoholic_lifestyle_num', 3 ) ;
// Bail if the section is disabled.
if ( 'disable' === $lifestyle ) {
	return;
}

$content_id = array();
for ( $i=1; $i <= $lifestyle_num; $i++ ) { 
    $content_id[] = get_theme_mod( "blogoholic_lifestyle_{$lifestyle}_" . $i );
}

$args = array(
    'post_type' => $lifestyle,
    'post__in' => (array)$content_id,   
    'orderby'   => 'post__in',
    'posts_per_page' => absint( $lifestyle_num ),
    'ignore_sticky_posts' => true,
);


?>
<div id="lifestyle" class="page-section no-padding-top">
    <div class="wrapper">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( get_theme_mod('blogoholic_lifestyle_title', __('Lifestyle', 'blogoholic') ) ) ?></h2>
        </div><!-- .section-header -->

        <div class="lifestyle-wrapper col-3">
        <?php
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
        	while ( $query->have_posts() ) {
        		$query->the_post();
        ?>
			<article class="hentry">
                <div class="featured-image" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url() ) ; ?>);">
                    <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
                </div>
                <div class="entry-container">
                    <header class="entry-header">
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    </header>

                    <div class="entry-content">
                        <p><?php echo esc_html( wp_trim_words( get_the_content(), 30 ) ) ?></p>                      
                    </div>
                
                    <div class="entry-meta">
                        <?php blogoholic_posted_on(); ?>
                    </div><!-- .entry-meta -->
                </div><!-- .entry-container -->
            </article> 

        <?php	}
        	wp_reset_postdata();
        }
        			
        ?>
		</div><!-- .grid-item -->
    </div><!-- .wrapper -->
</div>

