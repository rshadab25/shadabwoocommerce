<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Matrimony
 */
?>
</div><!-- #content -->
<footer id="colophon" class="site-footer">
	<?php do_action('matrimony_footer_hook_callback_action'); ?>
</footer><!-- #colophon -->
<div class="back-to-top">
    <a href="#masthead" title="<?php echo esc_attr__( 'Go to Top', 'matrimony' )?>"></a>
</div><!-- .back-to-top ends here -->
</div><!-- #page -->
<?php wp_footer(); ?>

</body>
</html>
