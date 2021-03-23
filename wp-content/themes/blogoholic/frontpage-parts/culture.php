<?php
/**
 * Template part for displaying front page imtroduction.
 *
 * @package Moral
 */


// Get the content type.
$culture = get_theme_mod( 'blogoholic_culture', 'disable' );

// Bail if the section is disabled.
if ( 'disable' === $culture ) {
    return;
}

$content_id = array();
for ( $i=1; $i <= 6; $i++ ) { 
    $content_id[] = get_theme_mod( "blogoholic_culture_{$culture}_" . $i );
}

$args = array(
    'post_type' => $culture,
    'post__in' => (array)$content_id,   
    'posts_per_page'    => 6,
    'ignore_sticky_posts' => true,
);

?>
<div id="art" class="page-section">
    <div class="wrapper">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'blogoholic_culture_title', __(' Art & Culture', 'blogoholic') ) ); ?></h2>
        </div>

        <div class="art-wrapper col-2 clear">
        <?php
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            $content = array();
            while ( $query->have_posts() ) {
                $query->the_post();
                $detail['image']   = get_the_post_thumbnail_url( );
                $detail['url']     = get_the_permalink( );
                $detail['id']      = get_the_ID();
                $detail['title']   = get_the_title();
                $detail['content']  = wp_trim_words( get_the_content(), 20, '  ....' );
                array_push( $content, $detail );
            }
            wp_reset_postdata();
        }
        if ( empty($content) ) {
            return;
        }
        $article_control = 1;
        $hentry_control = 0;
        for ($i=0; $i < 2 ; $i++) { 
            if ( $hentry_control == 1 ) {
                break;
            }
        ?>
            <div class="hentry">
                <?php 
                for ( $j=0; $j < count($content) ; $j++ ) { 
                    if ( in_array( count($content), array( 1, 2, 3, 4 ) ) ) {
                        $hentry_control = 1;
                    } 
                    if ( $article_control == 0 ) {
                        if ( in_array( $j, array(0,6) ) ) {
                            $j = $j+4;
                        }
                        if ( $j == count($content) ) {
                            break;
                        }
                    }
                ?>
                   <article>
                        <div class="featured-image" style="background-image: url(<?php echo esc_url( $content[$j]['image'] ) ?>);"> 
                            <a href="<?php echo esc_url( $content[$j]['url'] ) ; ?>" class="post-thumbnail-link"></a>
                        </div>                       
                        <div class="entry-container">
                            <header class="entry-header">
                                <h2 class="entry-title"><a href="<?php echo esc_url( $content[$j]['url'] ) ; ?>"><?php echo esc_html($content[$j]['title']); ?></a></h2>
                            </header>
                        
                            <div class="entry-meta">
                                <?php blogoholic_posted_on($content[$j]['id']) ; ?>
                            </div><!-- .entry-meta -->
                        </div><!-- .entry-container -->                    
                    </article>
                    <?php 
                        if ( $article_control == 1 ) {
                            if ( in_array( $j, array(3,9) ) ) {
                                $j = $j+2;
                            } 
                            if ( $j >= count($content) ) {
                                break;
                            }        
                        }

                    ?>
                <?php } $article_control = 0; ?>
            </div>
        <?php }  ?>
        </div><!-- .culture-wrapper -->
    </div><!-- .wrapper -->
</div><!-- #culture -->    

                   