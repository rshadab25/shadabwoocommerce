<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Matrimony
 */
?>
<aside id="secondary-right" class="widget-area-right">
	<?php if ( is_active_sidebar( 'matrimony-sidebar-right' ) ) :
		dynamic_sidebar( 'matrimony-sidebar-right' ); 
	endif; ?>
</aside>
