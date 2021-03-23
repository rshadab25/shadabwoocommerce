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
 * @package   WooCommerceBambora/Gateway
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2020, SkyVerge, Inc. (info@skyverge.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Bambora\Gateway;

use SkyVerge\WooCommerce\Bambora\API\Request\Payment;
use SkyVerge\WooCommerce\Bambora\Profile_Handler;
use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * Bambora credit card gateway class.
 *
 * @since 2.0.0
 */
class Credit_Card extends Framework\SV_WC_Payment_Gateway_Direct {


	/** @var string merchant ID */
	protected $merchant_id;

	/** @var string test merchant ID */
	protected $test_merchant_id;

	/** @var string API passcode */
	protected $api_passcode;

	/** @var string test API passcode */
	protected $test_api_passcode;

	/** @var API API handler instance */
	protected $api;


	/**
	 * Constructs the gateway.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			\SkyVerge\WooCommerce\Bambora\Plugin::CREDIT_CARD_GATEWAY_ID,
			wc_bambora(),
			array(
				'method_title'       => __( 'Bambora Credit Cards', 'woocommerce-gateway-bambora' ),
				'method_description' => __( 'Allow customers to securely pay using their credit cards with Bambora.', 'woocommerce-gateway-bambora' ),
				'supports'           => array(
					self::FEATURE_PRODUCTS,
					self::FEATURE_CARD_TYPES,
					self::FEATURE_PAYMENT_FORM,
					self::FEATURE_CREDIT_CARD_CHARGE,
					self::FEATURE_CREDIT_CARD_CHARGE_VIRTUAL,
					self::FEATURE_CREDIT_CARD_AUTHORIZATION,
					self::FEATURE_CREDIT_CARD_CAPTURE,
					self::FEATURE_CREDIT_CARD_PARTIAL_CAPTURE,
					self::FEATURE_DETAILED_CUSTOMER_DECLINE_MESSAGES,
					self::FEATURE_REFUNDS,
					self::FEATURE_VOIDS,
					self::FEATURE_TOKENIZATION,
					self::FEATURE_CUSTOMER_ID,
					self::FEATURE_ADD_PAYMENT_METHOD,
					self::FEATURE_TOKEN_EDITOR,
				),
				'environments' => array(
					self::ENVIRONMENT_PRODUCTION => esc_html_x( 'Production', 'software environment', 'woocommerce-gateway-bambora' ),
					self::ENVIRONMENT_TEST       => esc_html_x( 'Test', 'software environment', 'woocommerce-gateway-bambora' ),
				),
				'payment_type' => self::PAYMENT_TYPE_CREDIT_CARD,
			)
		);
	}


	/**
	 * Builds the payment token handler.
	 *
	 * @since 2.0.6
	 *
	 * @return Profile_Handler
	 */
	protected function build_payment_tokens_handler() {

		return new Profile_Handler( $this );
	}


	/**
	 * Adds the CSC gateway settings fields.
	 *
	 * This is overridden to skip adding these fields. Bambora requires the CSC
	 * be sent regardless, so it can't be disabled.
	 *
	 * @since 2.0.0
	 *
	 * @param array $form_fields gateway settings fields
	 * @return array
	 */
	protected function add_csc_form_fields( $form_fields ) {

		return $form_fields;
	}


	/**
	 * Gets the gateway form fields.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_method_form_fields()
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_method_form_fields() {

		return array(
			'merchant_id' => array(
				'title'       => __( 'Merchant ID', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your merchant ID, provided by Bambora.', 'woocommerce-gateway-bambora' ),
				'type'        => 'text',
				'class'       => 'environment-field production-field',
			),
			'test_merchant_id' => array(
				'title'       => __( 'Merchant ID', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your merchant ID, provided by Bambora.', 'woocommerce-gateway-bambora' ),
				'type'        => 'text',
				'class'       => 'environment-field test-field',
			),
			'api_passcode' => array(
				'title'       => __( 'API Passcode', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your API passcode, as configured in your Bambora account', 'woocommerce-gateway-bambora' ),
				'type'        => 'text',
				'class'       => 'environment-field production-field',
			),
			'test_api_passcode' => array(
				'title'       => __( 'API Passcode', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your API passcode, as configured in your Bambora account', 'woocommerce-gateway-bambora' ),
				'type'        => 'text',
				'class'       => 'environment-field test-field',
			),
		);
	}


	/**
	 * Initializes the payment form instance.
	 *
	 * @since 2.2.7
	 *
	 * @return Payment_Form
	 */
	protected function init_payment_form_instance() {

		return new Payment_Form( $this );
	}


	/**
	 * Enqueues the gateway assets.
	 *
	 * Adds the Bambora Custom Checkout JS.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::enqueue_gateway_assets()
	 *
	 * @since 2.0.0
	 */
	public function enqueue_gateway_assets() {

		if ( $this->is_available() ) {

			parent::enqueue_gateway_assets();

			wp_enqueue_script( 'bambora-custom-checkout', 'https://libs.na.bambora.com/customcheckout/1/customcheckout.js', array(), $this->get_plugin()->get_version() ); // we can only specify the major version
		}
	}


	/**
	 * Validates the payment fields.
	 *
	 * Overridden to check the email address for Bambora API limits if enabled.
	 *
	 * @since 2.0.6
	 *
	 * @return bool
	 */
	public function validate_fields() {

		/**
		 * Filters whether the billing email address must be sent with payment requests.
		 *
		 * The Bambora API has a limit of 64 characters for email addresses, and any request will fail if we truncate it
		 * in such a way that it becomes malformed. By default, our API handler will simply omit the email field if it's
		 * too long, but toggling this filter allows users to ensure an email address is always sent, and a more helpful
		 * message is displayed to customers if it's too long and can't be sent.
		 *
		 * Defaults to false, as most merchants will probably want to let payments through anyway. The exception is when
		 * creating a payment profile for new customers, as an email address is always required in that case.
		 *
		 * @since 2.0.6
		 *
		 * @param bool $require
		 */
		$require_email = apply_filters( 'wc_bambora_require_billing_email', false ) || ( ! $this->get_customer_id( get_current_user_id() ) && $this->get_payment_tokens_handler()->should_tokenize() );

		if ( $require_email && ( $email = Framework\SV_WC_Helper::get_posted_value( 'billing_email' ) ) && ! is_email( Framework\SV_WC_Helper::str_truncate( $email, 64, '' ) ) ) {

			Framework\SV_WC_Helper::wc_add_notice( __( 'Oops! It looks like your email address is too long. Please try again with a different email address.', 'woocommerce-gateway-bambora' ), 'error' );
			return false;
		}

		return parent::validate_fields();
	}


	/**
	 * Bypasses direct credit card validation.
	 *
	 * @since 2.0.0
	 *
	 * @param bool $is_valid whether the credit card fields are valid
	 * @return bool
	 */
	protected function validate_credit_card_fields( $is_valid ) {

		// ensure a single-use JS token as generated
		if ( ! Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-js-token' ) ) {
			$this->add_debug_message( 'Single-use token is missing', 'error' );
			$is_valid = false;
		}

		return $is_valid;
	}


	/**
	 * Gets an order with payment data added.
	 *
	 * @see Credit_Card::get_order()
	 * @see Credit_Card::add_payment_gateway_transaction_data()
	 * @see Payment::get_card_on_file_data()
	 *
	 * @since 2.0.0
	 *
	 * @param int $order_id order ID
	 * @return \WC_Order $order order object
	 */
	public function get_order( $order_id ) {

		$order = parent::get_order( $order_id );

		$order->payment->js_token  = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-js-token' );
		$order->payment->card_type = Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type( $order->payment->card_type );

		if ( ! empty( $order->payment->subscriptions ) ) {

			foreach ( $order->payment->subscriptions as $i => $subscription_data ) {

				/** @see Payment::get_card_on_file_data() */
				if ( is_object( $order->payment->subscriptions[ $i ] ) && ! empty( $order->payment->subscriptions[ $i ]->id ) ) {

					$order->payment->subscriptions[ $i ]->series_id = $this->get_order_meta( $order->payment->subscriptions[ $i ]->id, 'series_id' );
				}
			}
		}

		return $order;
	}


	/**
	 * Gets an order with subscriptions data added.
	 *
	 * @since 2.0.8
	 * @deprecated since 2.1.1
	 *
	 * TODO remove this deprecated method by version 3.0.0 or by September 2020 {FN 2019-09-09}
	 *
	 * @param \WC_Order $order order object
	 * @return \WC_Order
	 */
	protected function get_order_with_subscription_data( \WC_Order $order ) {

		wc_deprecated_function( __METHOD__, '2.1.1', '\\SkyVerge\\WooCommerce\\Bambora\\Gateway\\Credit_Card::get_order()' );

		return $order;
	}


	/**
	 * Gets an order with refund payment data added.
	 *
	 * Unlike most gateways, the transaction id of the capture must be used
	 * when processing refunds instead of the original transaction id.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_order_for_refund()
	 *
	 * @since 2.0.3
	 *
	 * @param \WC_Order|int $order order being processed
	 * @param float $amount refund amount
	 * @param string $reason optional refund reason text
	 * @return \WC_Order object with refund information attached
	 */
	protected function get_order_for_refund( $order, $amount, $reason ) {

		$order = parent::get_order_for_refund( $order, $amount, $reason );

		// check to see if a capture trans id exists
		$capture_trans_id = $this->get_order_meta( $order, 'capture_trans_id' );

		if ( $capture_trans_id ) {
			$order->refund->trans_id = $capture_trans_id;
		}

		return $order;
	}


	/**
	 * Gets a user's customer ID.
	 *
	 * Overridden to prevent auto-creation, as the Bambora API returns a unique
	 * customer ID that we need to use.
	 *
	 * @since 2.0.0
	 *
	 * @param int $user_id WordPress user ID
	 * @param array $args request args - @see Framework\SV_WC_Payment_Gateway::get_customer_id()
	 * @return string
	 */
	public function get_customer_id( $user_id, $args = array() ) {

		$args['autocreate'] = false;

		return parent::get_customer_id( $user_id, $args );
	}


	/**
	 * Gets the customer ID for a guest.
	 *
	 * A customer ID must exist in Bambora before it can be used so a guest
	 * customer ID cannot be generated on the fly. This ensures a customer is
	 * created when a payment method is tokenized for transactions such as a
	 * pre-order guest purchase.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return string|false
	 */
	public function get_guest_customer_id( \WC_Order $order ) {

		// is there a customer id already tied to this order?
		if ( $customer_id = $this->get_order_meta( $order, 'customer_id' ) ) {
			return $customer_id;
		}

		// default to false as a customer must be created first
		return false;
	}


	/**
	 * Gets the API handler.
	 *
	 * @since 2.0.0
	 *
	 * @return API
	 */
	public function get_api() {

		if ( null === $this->api ) {
			$this->api = new \SkyVerge\WooCommerce\Bambora\API( $this->get_merchant_id(), $this->get_api_passcode(), $this->get_id() );
		}

		return $this->api;
	}


	/**
	 * Determines if the gateway is properly configured to perform transactions.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_configured() {

		return $this->get_merchant_id() && $this->get_api_passcode();
	}


	/**
	 * Gets the merchant ID.
	 *
	 * @since 2.0.0
	 *
	 * @param string $environment_id gateway environment ID
	 * @return string
	 */
	public function get_merchant_id( $environment_id = null ) {

		if ( null === $environment_id ) {
			$environment_id = $this->get_environment();
		}

		return self::ENVIRONMENT_PRODUCTION === $environment_id ? $this->merchant_id : $this->test_merchant_id;
	}


	/**
	 * Gets the hash key.
	 *
	 * @since 2.0.0
	 *
	 * @param string $environment_id gateway environment ID
	 * @return string
	 */
	public function get_api_passcode( $environment_id = null ) {

		if ( null === $environment_id ) {
			$environment_id = $this->get_environment();
		}

		return self::ENVIRONMENT_PRODUCTION === $environment_id ? $this->api_passcode : $this->test_api_passcode;
	}


	/**
	 * Determines whether CSC is enabled.
	 *
	 * The CSC is always enabled.
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 */
	public function csc_enabled() {

		return true;
	}


	/**
	 * Determines whether tokenization should be performed before the payment.
	 *
	 * @since 2.0.0
	 *
	 * @return true
	 */
	public function tokenize_before_sale() {

		return true;
	}


	/**
	 * Performs a credit card transaction for the given order and returns the
	 * result.
	 *
	 * @since 2.0.6
	 *
	 * @param \WC_Order $order the order object
	 * @param Framework\SV_WC_Payment_Gateway_API_Response $response optional credit card transaction response
	 * @return Framework\SV_WC_Payment_Gateway_API_Response the response
	 * @throws Framework\SV_WC_Plugin_Exception network timeouts, etc
	 */
	protected function do_credit_card_transaction( $order, $response = null ) {

		// trigger a another update in case a new method was added
		if ( ! empty( $order->payment->token ) ) {
			$this->update_transaction_payment_method( $order );
		}

		return parent::do_credit_card_transaction( $order, $response );
	}


	/**
	 * Adds extra order data after a successful transaction.
	 *
	 * @see Payment::get_card_on_file_data()
	 * @see Credit_Card::get_order()
	 *
	 * @since 2.0.8
	 *
	 * @param \WC_Order $order order object
	 * @param Framework\SV_WC_Payment_Gateway_API_Customer_Response|\SkyVerge\WooCommerce\Bambora\API\Response\Payment $response API response object
	 */
	public function add_payment_gateway_transaction_data( $order, $response ) {

		parent::add_payment_gateway_transaction_data( $order, $response );

		if ( is_callable( [ $response, 'get_series_id' ] ) && $response->get_series_id() ) {

			$this->update_order_meta( $order, 'series_id', $response->get_series_id() );

			// if there was a subscription, we need to attach a series ID to it
			if ( ! empty( $order->payment->subscriptions ) ) {

				foreach ( $order->payment->subscriptions as $subscription_data ) {

					if ( is_object( $subscription_data ) && ! empty( $subscription_data->id ) ) {

						// we only record the series ID on the first subscription in order
						$this->update_order_meta( $subscription_data->id, 'series_id', $response->get_series_id() );
						break;
					}
				}
			}
		}
	}


}
