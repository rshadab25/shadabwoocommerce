<?php
/**
 * Template part for displaying front page imtroduction.
 *
 * @package Moral
 */


// Get the content type.
$blog = get_theme_mod( 'blogoholic_blog', 'disable' );
$blog_num = get_theme_mod( 'blogoholic_blog_num', 6 ) ;
// Bail if the section is disabled.
if ( 'disable' === $blog ) {
    return;
}


$cat_content_id = get_theme_mod( 'blogoholic_blog_cat' );
$args = array(
    'cat' => $cat_content_id,   
    'posts_per_page' =>  absint( $blog_num ),
);
   
?>
<div id="inner-content-wrapper" class="page-section no-padding-top">
    <div class="wrapper">
        <div class="section-header">
           <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'blogoholic_blog_title', __('More Articles', 'blogoholic') ) ); ?></h2>
        </div><!-- .section-header -->

        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                <div class="archive-blog-wrapper clear">
                <?php  
                if ( $blog == 'recent' ) {
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' =>  absint( $blog_num ),
                    );
                }
                $query = new WP_Query( $args );

                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                    ?>
                    <article class="has-post-thumbnail">
                        <div class="featured-image" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
                            <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
                        </div><!-- .featured-image -->

                        <div class="entry-container">
                            <span class="cat-links">
                                <?php the_category( '', '' ); ?>
                            </span>

                            <header class="entry-header">
                                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            </header>

                            <div class="entry-content">
                                <p>
                                    <?php echo esc_html(wp_trim_words( get_the_content(), 30, '  ...' )); ?>
                                    
                                </p>
                            </div><!-- .entry-content -->

                            <div class="entry-meta">
                                <?php blogoholic_posted_on() ; ?>
                            </div><!-- .entry-meta -->
                        </div><!-- .entry-container -->
                    </article>   

                    <?php 
                    }
                }
                wp_reset_postdata();
                ?>
                </div><!-- .archive-blog-wrapper -->
                
            </main><!-- #main -->
        </div><!-- #primary -->

        <aside id="secondary" class="widget-area" role="complementary">
            <?php if ( is_active_sidebar( 'blog-sidebar' ) ) {
                dynamic_sidebar( 'blog-sidebar' ); 
            } ?>
        </aside><!-- #secondary -->
    </div><!-- .wrapper -->
</div><!-- #inner-content-wrapper-->