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

namespace SkyVerge\WooCommerce\Bambora\Legacy;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The base Bambora (Legacy) API class.
 *
 * @since 2.0.0
 */
class API extends Framework\SV_WC_API_Base implements Framework\SV_WC_Payment_Gateway_API {


	/** @var string API request URL */
	protected $request_uri = 'https://www.beanstream.com/scripts/process_transaction.asp'; // test & production use the same endpoint

	/** @var Gateway gateway object */
	protected $gateway;

	/** @var \WC_Order WooCommerce order object */
	protected $order;


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 *
	 * @param Gateway $gateway gateway object
	 */
	public function __construct( Gateway $gateway ) {

		$this->gateway = $gateway;

		$this->set_response_handler( 'SkyVerge\WooCommerce\Bambora\Legacy\API\Response' );
	}


	/**
	 * Performs a credit card charge.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order WooCommerce order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function credit_card_charge( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request();

		$request->set_charge_data( $order );

		return $this->perform_request( $request );
	}


	/**
	 * Performs a credit card authorization.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order WooCommerce order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function credit_card_authorization( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request();

		$request->set_authorization_data( $order );

		return $this->perform_request( $request );
	}


	/** Tokenization methods **************************************************/


	/**
	 * Determines if the API supports getting tokenized payment methods.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_API::supports_get_tokenized_payment_methods()
	 *
	 * @since 2.0.0
	 *
	 * @return false
	 */
	public function supports_get_tokenized_payment_methods() {

		return false;
	}


	/**
	 * Determines if the API supports removing tokenized payment methods.
	 *
	 * @since 2.0.5
	 *
	 * @return false
	 */
	public function supports_update_tokenized_payment_method() {

		return false;
	}


	/**
	 * Determines if the API supports removing tokenized payment methods.
	 *
	 * @since 2.0.0
	 *
	 * @return false
	 */
	public function supports_remove_tokenized_payment_method() {

		return false;
	}


	/** Validation methods ****************************************************/


	/**
	 * Validates the response for errors before parsing the data.
	 *
	 * @see Framework\SV_WC_API_Base::do_pre_parse_response_validation()
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 * @throws Framework\SV_WC_API_Exception
	 */
	protected function do_pre_parse_response_validation() {

		// catch any empty responses
		if ( empty( $this->raw_response_body ) ) {
			throw new Framework\SV_WC_API_Exception( 'Response body is empty' );
		}

		return true;
	}


	/**
	 * Validates the response for errors after parsing the data.
	 *
	 * @see Framework\SV_WC_API_Base::do_post_parse_response_validation()
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 */
	protected function do_post_parse_response_validation() {

		return true;
	}


	/** Helper methods ********************************************************/


	/**
	 * Builds and returns a new API request object.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type request type to get
	 * @return API\Request
	 */
	protected function get_new_request( $type = null ) {

		/**
		 * Filters the Bambora Legacy API merchant ID.
		 *
		 * @since 2.0.0
		 *
		 * @param string $merchant_id API merchant ID
		 * @param \WC_Order|null $order order object
		 * @param Gateway $gateway gateway object
		 */
		$merchant_id = apply_filters( 'wc_beanstream_legacy_merchant_id', $this->get_gateway()->get_merchant_id(), $this->get_order(), $this->get_gateway() );

		/**
		 * Filters the Bambora Legacy API hash key.
		 *
		 * @since 2.0.0
		 *
		 * @param string $hash_key API hash key
		 * @param \WC_Order|null $order order object
		 * @param Gateway $gateway gateway object
		 */
		$hash_key = apply_filters( 'wc_beanstream_legacy_hash_key', $this->get_gateway()->get_hash_key(), $this->get_order(), $this->get_gateway() );

		/**
		 * Filters the Bambora Legacy API hash algorithm.
		 *
		 * @since 2.0.0
		 *
		 * @param string $hash_algorithm API hash algorithm
		 * @param \WC_Order|null $order order object
		 * @param Gateway $gateway gateway object
		 */
		$hash_algorithm = apply_filters( 'wc_beanstream_legacy_hash_algorithm', $this->get_gateway()->get_hash_algorithm(), $this->get_order(), $this->get_gateway() );

		return new API\Request( $merchant_id, $hash_key, $hash_algorithm );
	}


	/**
	 * Gets the order associated with the request.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Order|null
	 */
	public function get_order() {

		return $this->order;
	}


	/**
	 * Gets the ID for the API.
	 *
	 * This is used primarily to namespace the action name for broadcasting
	 * requests.
	 *
	 * @see Framework\SV_WC_API_Base::get_api_id()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_api_id() {

		return $this->get_gateway()->get_id();
	}


	/**
	 * Gets the gateway object.
	 *
	 * @since 2.0.0
	 *
	 * @return Gateway
	 */
	protected function get_gateway() {

		return $this->gateway;
	}


	/**
	 * Gets the main plugin instance.
	 *
	 * @see Framework\SV_WC_API_Base::get_plugin()
	 *
	 * @since 2.0.0
	 *
	 * @return Plugin
	 */
	protected function get_plugin() {

		return wc_bambora();
	}


	/* No-op methods **********************************************************/


	/**
	 * No-op: Bambora Legacy does not support captures.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function credit_card_capture( \WC_Order $order ) { }


	/**
	 * No-op: Bambora Legacy does not support eCheck transactions.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function check_debit( \WC_Order $order ) { }


	/**
	 * No-op: Bambora Legacy does not support refunds.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function refund( \WC_Order $order ) { }


	/**
	 * No-op: Bambora Legacy does not support voids.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function void( \WC_Order $order ) { }


	/**
	 * No-op: Bambora Legacy does not support tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function tokenize_payment_method( \WC_Order $order ) { }


	/**
	 * No-op: Bambora Legacy does not support tokenization.
	 *
	 * @since 2.0.5
	 *
	 * @param \WC_Order $order order object
	 */
	public function update_tokenized_payment_method( \WC_Order $order ) { }


	/**
	 * No-op: Bambora Legacy does not support tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token payment method token
	 * @param string $customer_id unique customer ID
	 */
	public function remove_tokenized_payment_method( $token, $customer_id ) { }


	/**
	 * No-op: Bambora Legacy does not support tokenization.
	 *
	 * @since 2.0.0
	 *
	 * @param string $customer_id unique customer ID
	 */
	public function get_tokenized_payment_methods( $customer_id ) { }


}
