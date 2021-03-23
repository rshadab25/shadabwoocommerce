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

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The base Bambora API class.
 *
 * @since 2.0.0
 */
class API extends Framework\SV_WC_API_Base implements Framework\SV_WC_Payment_Gateway_API {


	/** the Bambora API version to use */
	const API_VERSION = 'v1';


	/** @var string merchant ID */
	protected $merchant_id;

	/** @var string API passcode */
	protected $api_passcode;

	/** @var string payment gateway ID */
	protected $gateway_id;

	/** @var \WC_Order WooCommerce order object */
	protected $order;


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 *
	 * @param string $merchant_id merchant ID
	 * @param string $api_passcode API passcode
	 * @param string $gateway_id payment gateway ID calling this API
	 */
	public function __construct( $merchant_id, $api_passcode, $gateway_id ) {

		$this->merchant_id  = $merchant_id;
		$this->api_passcode = $api_passcode;
		$this->gateway_id   = $gateway_id;

		$this->request_uri = 'https://api.na.bambora.com/' . self::API_VERSION;

		$this->set_request_content_type_header( 'application/json' );
		$this->set_request_accept_header( 'application/json' );
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

		$request = $this->get_new_request( 'payment' );

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

		$request = $this->get_new_request( 'payment' );

		$request->set_authorization_data( $order );

		return $this->perform_request( $request );
	}


	/**
	 * Captures an authorized credit card payment.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function credit_card_capture( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( 'payment' );

		$request->set_capture_data( $order );

		return $this->perform_request( $request );
	}


	/**
	 * Refunds a transaction.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function refund( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( 'payment' );

		$request->set_refund_data( $order );

		return $this->perform_request( $request );
	}


	/**
	 * Voids a transaction.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function void( \WC_Order $order ) {

		$this->order = $order;

		$request = $this->get_new_request( 'payment' );

		$request->set_void_data( $order );

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
	 * @return true
	 */
	public function supports_get_tokenized_payment_methods() {

		return true;
	}


	/**
	 * Determines if the API supports removing tokenized payment methods.
	 *
	 * The Bambora API does support updating customer _profiles_, but profiles
	 * can contain many cards, and we can't target a specific card's billing
	 * details.
	 *
	 * @since 2.0.5
	 *
	 * @return false
	 */
	public function supports_update_tokenized_payment_method() {

		return true;
	}


	/**
	 * Determines if the API supports removing tokenized payment methods.
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 */
	public function supports_remove_tokenized_payment_method() {

		return true;
	}


	/**
	 * Tokenizes a payment method.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function tokenize_payment_method( \WC_Order $order ) {

		$this->order = $order;

		$request     = $this->get_new_request( 'profile' );
		$new_profile = false;

		// either add a new method or create a new profile
		if ( ! empty( $order->customer_id ) ) {

			$request->set_add_method_data( $order );

		} else {

			$request->set_create_profile_data( $order );

			$new_profile = true;
		}

		$response = $this->perform_request( $request );

		if ( $response->transaction_approved() ) {

			// the API doesn't return the new method ID/token, so we have to get all of them
			$profile_response = $this->get_tokenized_payment_methods( $response->get_customer_id() );

			$cards = $profile_response->get_payment_tokens();

			if ( ! empty( $cards ) ) {

				// get the last item, which will be the one we just added
				$new_method = end( $cards );

				// set it in the response so that the gateway has a value to store
				if ( $new_method instanceof Framework\SV_WC_Payment_Gateway_Payment_Token ) {
					$response->set_payment_token( $new_method );
				}
			}

			// sanity check to make sure a new token was added
			if ( ! $response->get_payment_token() ) {
				throw new Framework\SV_WC_API_Exception( __( 'Payment method could not be added', 'woocommerce-gateway-bambora' ) );
			}

		// if trying to create a new profile and we received a 500 error, that likely means a duplicate
		} elseif ( $new_profile && 500 === (int) $this->get_response_code() ) {

			throw new Framework\SV_WC_API_Exception( __( 'New payment profile could not be created with card data duplicated from an existing profile', 'woocommerce-gateway-bambora' ) );
		}

		return $response;
	}


	/**
	 * Updates a tokenized payment method.
	 *
	 * @see API::supports_update_tokenized_payment_method()
	 *
	 * @since 2.0.5
	 *
	 * @param \WC_Order $order order object
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function update_tokenized_payment_method( \WC_Order $order ) {

		$request = $this->get_new_request( 'profile' );

		$request->set_update_method_data( $order );

		return $this->perform_request( $request );
	}


	/**
	 * Removes a tokenized payment method.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token payment method token
	 * @param string $customer_id unique customer ID
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function remove_tokenized_payment_method( $token, $customer_id ) {

		$request = $this->get_new_request( 'profile' );

		$request->set_remove_method_data( $token, $customer_id );

		return $this->perform_request( $request );
	}


	/**
	 * Gets the tokenized payment methods for a customer ID.
	 *
	 * @since 2.0.0
	 *
	 * @param string $customer_id unique customer ID
	 * @return API\Response
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function get_tokenized_payment_methods( $customer_id ) {

		$request = $this->get_new_request( 'profile' );

		$request->set_get_methods_data( $customer_id );

		return $this->perform_request( $request );
	}


	/** Validation methods ****************************************************/


	/**
	 * Performs the API request and returns the parsed response.
	 *
	 * Overridden to add authenication headers and allow filtering the merchant ID.
	 *
	 * @since 2.0.0
	 *
	 * @param API\Request $request request object
	 * @return API\Response response object
	 * @throws Framework\SV_WC_API_Exception
	 */
	protected function perform_request( $request ) {

		/**
		 * Filters the Bambora API merchant ID.
		 *
		 * @since 2.0.0
		 *
		 * @param string $merchant_id API merchant ID
		 * @param \WC_Order|null $order order object
		 */
		$this->merchant_id = apply_filters( 'wc_bambora_api_request_merchant_id', $this->merchant_id, $this->get_order() );

		/**
		 * Filters the Bambora API passcode.
		 *
		 * @since 2.0.0
		 *
		 * @param string $api_passcode API passcode
		 * @param \WC_Order|null $order order object
		 */
		$this->api_passcode = apply_filters( 'wc_bambora_api_request_passcode', $this->api_passcode, $this->get_order() );

		$this->set_request_header( 'Authorization', 'Passcode ' . base64_encode( "{$this->merchant_id}:{$this->api_passcode}" ) );

		return parent::perform_request( $request );
	}


	/**
	 * Validates the response for errors before parsing the data.
	 *
	 * @see Framework\SV_WC_API_Base::do_pre_parse_response_validation()
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 */
	protected function do_pre_parse_response_validation() {

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
	 * @throws Framework\SV_WC_API_Exception
	 */
	protected function do_post_parse_response_validation() {

		$response_code = (int) $this->get_response_code();

		// if not a 200 response and an error code is returned
		if ( $response_code > 200 && ! $response_code < 300 && $this->get_response()->get_code() > 1 ) {
			throw new Framework\SV_WC_API_Exception( $this->get_response()->get_status_message() );
		}

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
	 * @throws Framework\SV_WC_API_Exception
	 */
	protected function get_new_request( $type = '' ) {

		switch ( $type ) {

			case 'payment':
				$request_handler  = '\\SkyVerge\\WooCommerce\\Bambora\\API\\Request\\Payment';
				$response_handler = '\\SkyVerge\\WooCommerce\\Bambora\\API\\Response\\Payment';
			break;

			case 'profile':
				$request_handler  = '\\SkyVerge\\WooCommerce\\Bambora\\API\\Request\\Profile';
				$response_handler = '\\SkyVerge\\WooCommerce\\Bambora\\API\\Response\\Profile';
			break;

			default:
				throw new Framework\SV_WC_API_Exception( 'Invalid request type' );
		}

		$this->set_response_handler( $response_handler );

		return new $request_handler;
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
	 * Get the ID for the API.
	 *
	 * Used primarily to namespace the action name for broadcasting requests.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_api_id() {

		return $this->gateway_id;
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
	 * No-op: Bambora Legacy does not support eCheck transactions.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function check_debit( \WC_Order $order ) { }


}
