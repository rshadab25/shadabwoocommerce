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
 * @package   WooCommerceBambora/API
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2020, SkyVerge, Inc. (info@skyverge.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Bambora;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

/**
 * The My Payment Methods handler.
 *
 * @since 2.2.8
 */
class My_Payment_Methods extends Framework\SV_WC_Payment_Gateway_My_Payment_Methods {


	/**
	 * Maybe enqueues the styles and scripts.
	 *
	 * Only enqueue if there are payment methods to manage.
	 *
	 * @since 2.2.8
	 */
	public function maybe_enqueue_styles_scripts() {

		parent::maybe_enqueue_styles_scripts();

		if ( $this->has_tokens ) {
			wp_enqueue_script( 'wc-bambora-my-payment-methods', $this->get_plugin()->get_plugin_url() . '/assets/js/frontend/wc-bambora-my-payment-methods.min.js', [ 'sv-wc-payment-gateway-my-payment-methods-v5_8_1' ], $this->get_plugin()->get_version() );
		}
	}


	/**
	 * Gets the JS handler class name.
	 *
	 * @since 2.2.8
	 *
	 * @return string
	 */
	public function get_js_handler_class_name() {

		return 'WC_Bambora_My_Payment_Methods_Handler';
	}


}
