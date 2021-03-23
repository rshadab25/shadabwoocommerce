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

namespace SkyVerge\WooCommerce\Bambora\API;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The Bambora API base request class.
 *
 * @since 2.0.0
 */
class Request extends Framework\SV_WC_API_JSON_Request implements Framework\SV_WC_Payment_Gateway_API_Request {


	/**
	 * Gets the billing data from an order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_billing_data( \WC_Order $order ) {

		$data = $this->get_address_data( $order, 'billing' );

		$data['phone_number'] = Framework\SV_WC_Helper::str_truncate( preg_replace( '/[^0-9]/', '', $order->get_billing_phone( 'edit' ) ), 20, '' ); // digits only

		$email = Framework\SV_WC_Helper::str_truncate( $order->get_billing_email( 'edit' ), 64, '' );

		// the Bambora API will fail payment if the email address isn't valid, so check that it's still intact after truncating
		if ( is_email( $email ) ) {
			$data['email_address'] = $email;
		}

		return $data;
	}


	/**
	 * Gets the shipping data from an order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_shipping_data( \WC_Order $order ) {

		return $this->get_address_data( $order, 'shipping' );
	}


	/**
	 * Gets the shipping data from an order, formatted for the Bambora API.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param string $type address type, like billing or shipping
	 * @return array
	 */
	protected function get_address_data( \WC_Order $order, $type = 'billing' ) {

		$country  = $this->get_order_prop( $order, "{$type}_country" );
		$province = '';

		if ( $country ) {
			$province = in_array( $country, [ 'US', 'CA' ], true ) ? $this->get_order_prop( $order, "{$type}_state" ) : '--';
		}

		$name = 'shipping' === $type && trim( $order->get_formatted_shipping_full_name() ) ? $order->get_formatted_shipping_full_name() : $order->get_formatted_billing_full_name();

		$address = [
			'name'          => Framework\SV_WC_Helper::str_truncate( $name, 64, '' ),
			'address_line1' => Framework\SV_WC_Helper::str_truncate( $this->get_order_prop( $order, "{$type}_address_1" ), 64 ),
			'address_line2' => Framework\SV_WC_Helper::str_truncate( $this->get_order_prop( $order, "{$type}_address_2" ), 64 ),
			'city'          => Framework\SV_WC_Helper::str_truncate( $this->get_order_prop( $order, "{$type}_city" ), 32 ),
			'province'      => Framework\SV_WC_Helper::str_truncate( $province, 2 ),
			'country'       => Framework\SV_WC_Helper::str_truncate( $country, 2 ),
			'postal_code'   => Framework\SV_WC_Helper::str_truncate( $this->get_order_prop( $order, "{$type}_postcode" ), 16 ),
		];

		return array_filter( $address );
	}


	/**
	 * Gets the unfiltered value for an order property.
	 *
	 * @since 2.2.0
	 *
	 * @param \WC_Order $object the order object
	 * @param string $prop the property name
	 * @return mixed
	 */
	private function get_order_prop( $order, $prop ) {

		return is_callable( [ $order, "get_{$prop}" ] ) ? $order->{"get_{$prop}"}( 'edit' ) : null;
	}


	/**
	 * Gets the request data.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_data() {

		/**
		 * Filters the Bambora API request data.
		 *
		 * @since 2.0.0
		 *
		 * @param array $data request data
		 */
		$this->data = (array) apply_filters( 'wc_bambora_credit_card_request_data', $this->data );

		return $this->data;
	}


}
