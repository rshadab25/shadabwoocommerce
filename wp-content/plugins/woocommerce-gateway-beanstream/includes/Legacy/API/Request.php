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

namespace SkyVerge\WooCommerce\Bambora\Legacy\API;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The Bambora Legacy API request class.
 *
 * @since 2.0.0
 */
class Request implements Framework\SV_WC_Payment_Gateway_API_Request {

	/** charge transaction type */
	const TRANSACTION_TYPE_CHARGE = 'P';

	/** charge transaction type */
	const TRANSACTION_TYPE_AUTHORIZATION = 'PA';


	/** @var string request method */
	protected $method = 'POST';

	/** @var array request parameters */
	protected $params = array();

	/** @var array request data */
	protected $data = array();

	/** @var \WC_Order WooCommerce order object */
	protected $order;

	/** @var string API merchant ID */
	protected $merchant_id;

	/** @var string API hash key */
	protected $hash_key;

	/** @var string API hash algorithm */
	protected $hash_algorithm;


	/**
	 * Constructs the class.
	 *
	 * We need to pass the merchant ID, hash key, and hash algorithm to the
	 * request because those params are included directly in the request data.
	 *
	 * @since 2.0.0
	 *
	 * @param string $merchant_id API merchant ID
	 * @param string $hash_key API hash key
	 * @param string $hash_algorithm API hash algorithm
	 */
	public function __construct( $merchant_id, $hash_key, $hash_algorithm ) {

		$this->merchant_id    = $merchant_id;
		$this->hash_key       = $hash_key;
		$this->hash_algorithm = $hash_algorithm;
	}


	/**
	 * Sets the credit card charge transaction data.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_charge_data( \WC_Order $order ) {

		$this->order = $order;

		$this->set_transaction_data( self::TRANSACTION_TYPE_CHARGE );
	}


	/**
	 * Sets the credit card authorization transaction data.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_authorization_data( \WC_Order $order ) {

		$this->order = $order;

		$this->set_transaction_data( self::TRANSACTION_TYPE_AUTHORIZATION );
	}


	/**
	 * Sets the necessary transaction data.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type transaction type, like auth-only or charge
	 */
	protected function set_transaction_data( $type ) {

		$order = $this->get_order();

		$countries_with_state = array(
			'US',
			'CA',
		);

		$billing_country = $order->get_billing_country( 'edit' );
		$billing_state   = in_array( $billing_country, $countries_with_state, true ) ? $order->get_billing_state( 'edit' ) : '--';

		$this->data = array(
			'merchant_id'     => $this->merchant_id,
			'requestType'     => 'BACKEND',
			'trnType'         => $type,
			'trnOrderNumber'  => ltrim( $order->get_order_number(), '#' ),
			'trnAmount'       => $order->payment_total,
			'trnCardOwner'    => $order->get_formatted_billing_full_name(),
			'trnCardNumber'   => $order->payment->account_number,
			'trnExpMonth'     => $order->payment->exp_month,
			'trnExpYear'      => $order->payment->exp_year,
			'trnCardCvd'      => $order->payment->csc,
			'ordName'         => $order->get_formatted_billing_full_name(),
			'ordAddress1'     => $order->get_billing_address_1( 'edit' ),
			'ordAddress2'     => $order->get_billing_address_2( 'edit' ),
			'ordCity'         => $order->get_billing_city( 'edit' ),
			'ordProvince'     => $billing_state,
			'ordCountry'      => $billing_country,
			'ordPostalCode'   => $order->get_billing_postcode( 'edit' ),
			'ordPhoneNumber'  => $order->get_billing_phone( 'edit' ),
			'ordEmailAddress' => $order->get_billing_email( 'edit' ),
		);

		if ( $order->has_shipping_address() ) {

			$shipping_country = $order->get_shipping_country( 'edit' );
			$shipping_state   = in_array( $shipping_country, $countries_with_state, true ) ? $order->get_shipping_state( 'edit' ) : '--';

			$this->data = array_merge( $this->data, array(
				'shipName'        => ( trim( $order->get_formatted_shipping_full_name() ) ) ? $order->get_formatted_shipping_full_name() : $order->get_formatted_billing_full_name(),
				'shipAddress1'    => $order->get_shipping_address_1( 'edit' ),
				'shipAddress2'    => $order->get_shipping_address_2( 'edit' ),
				'shipCity'        => $order->get_shipping_city( 'edit' ),
				'shipProvince'    => $shipping_state,
				'shipPostalCode'  => $order->get_shipping_postcode( 'edit' ),
				'shipCountry'     => $shipping_country,
			) );
		}

		$this->data['customerIp'] = $order->get_customer_ip_address( 'edit' );
	}


	/**
	 * Gets the order object for the request.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Order
	 */
	protected function get_order() {

		return $this->order;
	}


	/**
	 * Gets the request method.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_method() {

		return $this->method;
	}


	/**
	 * Gets the request path.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_path() {

		return '';
	}


	/**
	 * Gets the request query params.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_params() {

		return $this->params;
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
		 * Filters the Bambora Legacy API request data.
		 *
		 * @since 1.7.2
		 *
		 * @param array $data request data
		 * @param \WC_Order $order order object
		 * @param \SkyVerge\WooCommerce\Bambora\Legacy\Gateway $gateway gateway object
		 */
		$this->data = (array) apply_filters( 'wc_beanstream_credit_card_legacy_request_data', $this->data, $this->get_order(), wc_bambora()->get_gateway( \SkyVerge\WooCommerce\Bambora\Plugin::LEGACY_GATEWAY_ID ) );

		return $this->data;
	}


	/**
	 * Gets the string representation of the request data.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function to_string() {

		$string = http_build_query( $this->get_data(), '', '&' );

		// add the hash value if a hash key is present
		if ( $this->hash_key ) {

			$algorithm  = $this->hash_algorithm;
			$hash_value = function_exists( $algorithm ) ? $algorithm( $string . $this->hash_key ) : md5( $string . $this->hash_key );

			$string .= "&hashValue={$hash_value}";
		}

		return $string;
	}


	/**
	 * Gets the string representation of the request data with all sensitive
	 * information masked or removed.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function to_string_safe() {

		$string = $this->to_string();

		// mask the merchant ID
		if ( ! empty( $this->data['merchant_id'] ) ) {
			$string = str_replace( $this->data['merchant_id'], str_repeat( '*', strlen( $this->data['merchant_id'] ) ), $string );
		}

		// mask the card number
		if ( ! empty( $this->data['trnCardNumber'] ) ) {
			$string = str_replace( $this->data['trnCardNumber'], str_repeat( '*', strlen( $this->data['trnCardNumber'] ) ), $string );
		}

		// mask the card CVD
		if ( ! empty( $this->data['trnCardCvd'] ) ) {
			$string = str_replace( $this->data['trnCardCvd'], str_repeat( '*', strlen( $this->data['trnCardCvd'] ) ), $string );
		}

		return $string;
	}


}
