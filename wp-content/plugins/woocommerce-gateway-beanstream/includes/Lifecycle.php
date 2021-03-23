<?php
/**
 * WooCommerce Bambora Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Bambora Gateway to newer
 * versions in the future. If you wish to customize WooCommerce Bambora Gateway for your
 * needs please refer to http://docs.woocommerce.com/document/bambora/
 *
 * @package   WooCommerceBambora/Plugin
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2020, SkyVerge, Inc. (info@skyverge.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Bambora;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The Bambora plugin lifecycle handler.
 *
 * @since 2.0.5
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.7
	 *
	 * @param Plugin $plugin plugin instance
	 */
	public function __construct( Plugin $plugin ) {

		$this->upgrade_versions = [
			'2.0.0',
		];

		parent::__construct( $plugin );
	}


	/**
	 * Installs the plugin.
	 *
	 * @since 2.0.5
	 */
	protected function install() {

		// versions prior to 2.0.0 did not set a version option, so the upgrade method needs to be called manually
		if ( get_option( 'woocommerce_beanstream_settings' ) ) {

			$this->upgrade( '1.12.0' );

			/**
			 * Fires after the plugin has been updated.
			 *
			 * @since 2.0.0
			 */
			do_action( 'wc_' . $this->get_plugin()->get_id() . '_updated' );
		}
	}


	/**
	 * Upgrades to v2.0.0.
	 *
	 * @since 2.0.7
	 */
	protected function upgrade_to_2_0_0() {

		$legacy_settings = get_option( 'woocommerce_beanstream_settings', [] );

		if ( ! empty( $legacy_settings ) ) {

			update_option( 'wc_' . Plugin::PLUGIN_ID . '_legacy_active', 'yes' );

			$new_settings = [
				'enabled'          => isset( $legacy_settings['enabled'] ) ? $legacy_settings['enabled'] : 'no',
				'title'            => isset( $legacy_settings['title'] ) ? $legacy_settings['title'] : __( 'Credit Card', 'woocommerce-gateway-bambora' ),
				'description'      => isset( $legacy_settings['description'] ) ? $legacy_settings['description'] : __( 'Pay securely using your credit card.', 'woocommerce-gateway-bambora' ),
				'environment'      => Framework\SV_WC_Payment_Gateway::ENVIRONMENT_PRODUCTION, // pre-2.0 there was no environment setting, so assume production
				'merchant_id'      => isset( $legacy_settings['merchantid'] ) ? $legacy_settings['merchantid'] : '',
				'hash_key'         => isset( $legacy_settings['hashkey'] )    ? $legacy_settings['hashkey'] : '',
				'transaction_type' => isset( $legacy_settings['preauthonly'] ) && 'yes' === $legacy_settings['preauthonly'] ? Framework\SV_WC_Payment_Gateway::TRANSACTION_TYPE_AUTHORIZATION : Framework\SV_WC_Payment_Gateway::TRANSACTION_TYPE_CHARGE,
				'debug_mode'       => isset( $legacy_settings['debugon'] )     && 'yes' === $legacy_settings['debugon']     ? Framework\SV_WC_Payment_Gateway::DEBUG_MODE_LOG                 : Framework\SV_WC_Payment_Gateway::DEBUG_MODE_OFF,
			];

			// map card types
			if ( isset( $legacy_settings['cardtypes'] ) && is_array( $legacy_settings['cardtypes'] ) ) {

				$new_settings['card_types'] = [];

				foreach ( $legacy_settings['cardtypes'] as $card_type ) {

					switch ( $card_type ) {

						case 'MasterCard':
							$new_settings['card_types'][] = 'MC';
						break;

						case 'Visa':
							$new_settings['card_types'][] = 'VISA';
						break;

						case 'Discover':
							$new_settings['card_types'][] = 'DISC';
						break;

						case 'American Express':
							$new_settings['card_types'][] = 'AMEX';
						break;
					}
				}
			}

			update_option( 'woocommerce_beanstream_settings', $new_settings );

			// remove the hash key and add the Bambora gateway settings too
			// if the merchant switches to the new gateway, their settings will be populated as much as possible
			unset( $new_settings['hash_key'] );

			update_option( 'woocommerce_bambora_settings', $new_settings );
		}
	}


}
