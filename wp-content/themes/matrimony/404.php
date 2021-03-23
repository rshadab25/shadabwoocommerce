<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Matrimony
 */
get_header();
	$number_one = get_theme_mod('matrimony_404_num_one',4);
	$number_two = get_theme_mod('matrimony_404_num_two',4);
?>
	<div id="primary">
	    <main id="main" class="site-main">
	        <section class="error-404 not-found" style="background-image: url('<?php echo esc_url(get_theme_mod('matrimony_404_image','')); ?>') ";>
	            <div class="error-wrap">
	                <h4 class="error-title animated wow fadeInDown" data-wow-duration="1s">
	                	<?php if(!empty($number_one)){ ?>
	                    	<span><?php echo absint( $number_one );?></span>
	                    <?php } ?>
                    	<?php 
                    	$image_two = get_theme_mod('matrimony_404_image_two','');
                            if( !empty( $image_two) ) { ?>
                        		<span>
                        		   <img src="<?php echo esc_url($image_two);?>" />
                        		</span>
                        	<?php } else{ ?>
                        		<img src="<?php echo esc_url( get_template_directory_uri() )?>/assets/img/error-o.png" alt="">
                        	<?php } ?>
                    	<?php if(!empty($number_two)){ ?>
	                   		 <span><?php echo absint( $number_two );?></span>
	                    <?php } ?>
	                </h4>
	                <div class="error-description animated wow fadeInUp" data-wow-duration="1s">
	                    <p>
	                       <?php  esc_html_e('Page Not Found','matrimony')?>
	                    </p>
	                   <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php echo esc_html__('back to home','matrimony') ?></a>
	                </div>
	            </div>
	        </section>
	    </main><!--.site-main-->
	</div><!--#primary-->
<?php get_footer();
