<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Moral
 */

$default = blogoholic_get_default_mods();
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<!-- supports col-1, col-2, col-3 and col-4 -->
		<!-- supports unequal-width and equal-width -->
		<?php  
		$count = 0;
		for ( $i=1; $i <=4 ; $i++ ) { 
			if ( is_active_sidebar( 'footer-' . $i ) ) {
				$count++;
			}
		}
		
		if ( 0 !== $count ) : ?>
			<div class="footer-widgets-area page-section col-<?php echo esc_attr( $count );?>">
			    <div class="wrapper">
					<?php 
					for ( $j=1; $j <=4; $j++ ) { 
						if ( is_active_sidebar( 'footer-' . $j ) ) {
			    			echo '<div class="hentry">';
							dynamic_sidebar( 'footer-' . $j ); 
			    			echo '</div>';
						}
					}
					?>
				</div><!-- .wrapper -->
			</div><!-- .footer-widget-area -->

		<?php endif;
		 
		$footer_menu = get_theme_mod( 'blogoholic_enable_footer_social_menu', true );
		$footer_text = get_theme_mod( 'blogoholic_enable_footer_text', true );

		if ( $footer_menu || $footer_text ) :

			?>
			<div class="site-info">
				<!-- supports col-1 and col-2 -->
				<?php 

				?>
				<div class="wrapper">
					<?php if ( $footer_text ) { ?>
					    <div class="site-info-wrapper">
					        <?php echo wp_kses_post( get_theme_mod( 'blogoholic_copyright_txt', $default['blogoholic_copyright_txt'] ) ); ?>
					    </div><!-- .footer-copyright -->
					<?php } ?>
				    
				</div><!-- .wrapper -->    
				
			</div><!-- .site-info -->
		<?php endif; ?>
	</footer><!-- #colophon -->
	
	<?php  
	$backtop = get_theme_mod( 'blogoholic_back_to_top_enable', true );
	if ( $backtop ) { ?>
		<div class="backtotop"><?php echo blogoholic_get_svg( array( 'icon' => 'up-arrow' ) ); ?></div>
	<?php }	?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
