<?php
/**
 * Demo Import.
 *
 */

function blogoholic_ctdi_plugin_page_setup( $default_settings ) {
    $default_settings['menu_title']  = esc_html__( 'Moral Theme Demo Import' , 'blogoholic' );

    return $default_settings;
}
add_filter( 'cp-ctdi/plugin_page_setup', 'blogoholic_ctdi_plugin_page_setup' );


function blogoholic_ctdi_plugin_intro_text( $default_text ) {
    $default_text .= sprintf( '<p class="about-description">%1$s <a href="%2$s">%3$s</a></p>', esc_html__( 'Demo content files for blogoholic  Theme.', 'blogoholic' ),
    esc_url( 'https://themepalace.com/download/blogoholic' ), esc_html__( 'Click here for Demo File download', 'blogoholic' ) );
    return $default_text;
}
add_filter( 'cp-ctdi/plugin_intro_text', 'blogoholic_ctdi_plugin_intro_text' );