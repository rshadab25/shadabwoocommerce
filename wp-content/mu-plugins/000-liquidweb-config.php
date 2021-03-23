<?php 
/**
 * Plugin Name: Liquid Web Managed WordPress Configuration
 * Plugin URI:  https://www.liquidweb.com
 * Description: Configuration to support the Liquid Web Managed WordPress and WooCommerce platforms.
 * Author:      Liquid Web
 * Author URI:  https://www.liquidweb.com
 * Text Domain: liquid-web-mwp
 * Domain Path: /languages
 * Version:     1.0.0
 *
 * @package LiquidWeb\MWP\Config
 * @author  Liquid Web
 */

/** LW specific constants **/
define( 'LWMWP_SITE', true );
define( 'LWMWP_PLAN_NAME', 'Personal: Up to 10 Sites' );
defined('LWMWP_STAGING_SITE')    || define('LWMWP_STAGING_SITE', true);
defined( 'LWMWP_SITE_ENDPOINT' ) || define('LWMWP_SITE_ENDPOINT', 'https://app.pk9u5j45-liquidwebsites.com/api/sites/2/');
defined( 'LWMWP_API_TOKEN' )     || define('LWMWP_API_TOKEN',     '03c2eecf-6244-4d6b-804d-fa2af2736af4');

/** Fail2Ban **/
defined('WP_FAIL2BAN_BLOCK_USER_ENUMERATION') || define('WP_FAIL2BAN_BLOCK_USER_ENUMERATION', true);
