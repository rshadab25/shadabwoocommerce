<?php
 /**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Matrimony
 */
?>
<aside id="secondary" class="widget-area-left">
	<?php if ( is_active_sidebar( 'matrimony-sidebar-left' ) ) :
		dynamic_sidebar( 'matrimony-sidebar-left' ); 
	endif; ?>
</aside>
