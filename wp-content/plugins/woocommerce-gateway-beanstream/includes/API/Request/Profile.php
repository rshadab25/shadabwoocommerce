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

namespace SkyVerge\WooCommerce\Bambora\API\Request;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The Bambora API profile request class.
 *
 * @since 2.0.0
 */
class Profile extends \SkyVerge\WooCommerce\Bambora\API\Request {


	/** @var string API URL path */
	protected $path = '/profiles';


	/**
	 * Sets the data for creating a new payment profile.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_create_profile_data( \WC_Order $order ) {

		$this->data = $this->get_payment_method_data( $order );

		$this->data['billing'] = $this->get_billing_data( $order );
	}


	/**
	 * Sets the data for adding a payment method to a profile.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_add_method_data( \WC_Order $order ) {

		$this->path .= "/{$order->customer_id}/cards";

		$this->data = $this->get_payment_method_data( $order );
	}


	/**
	 * Sets the data for updating a payment method profile.
	 *
	 * Note that Bambora stores only one billing address per customer profile, so if the customer has multiple cards
	 * each with different billing addresses, this will be updated to the address of the latest card used for payment.
	 *
	 * @since 2.0.6
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_update_method_data( \WC_Order $order ) {

		$this->method = 'PUT';
		$this->path  .= "/{$order->customer_id}";

		$this->data = array(
			'billing' => $this->get_billing_data( $order ),
		);
	}


	/**
	 * Sets the data for removing a tokenized payment method.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token payment method token
	 * @param string $customer_id unique customer ID
	 */
	public function set_remove_method_data( $token, $customer_id ) {

		$this->method = 'DELETE';
		$this->path  .= "/{$customer_id}/cards/{$token}";
	}


	/**
	 * Sets the data for getting a customer's tokenized payment methods.
	 *
	 * @since 2.0.0
	 *
	 * @param string $customer_id unique customer ID
	 */
	public function set_get_methods_data( $customer_id ) {

		$this->method = 'GET';
		$this->path  .= "/{$customer_id}/cards";
	}


	/**
	 * Gets the data for adding a payment method.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_payment_method_data( \WC_Order $order ) {

		return array(
			'token' => array(
				'name' => Framework\SV_WC_Helper::str_truncate( $order->get_formatted_billing_full_name(), 64, '' ),
				'code' => $order->payment->js_token,
			),
		);
	}


}
