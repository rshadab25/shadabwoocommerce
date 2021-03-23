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

namespace SkyVerge\WooCommerce\Bambora\Legacy;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * Legacy Bambora (Beanstream) gateway class.
 *
 * @since 2.0.0
 */
class Gateway extends Framework\SV_WC_Payment_Gateway_Direct {


	/** @var string merchant ID */
	protected $merchant_id;

	/** @var string test merchant ID */
	protected $test_merchant_id;

	/** @var string hash key */
	protected $hash_key;

	/** @var string test hash key */
	protected $test_hash_key;

	/** @var string hash algorithm */
	protected $hash_algorithm;

	/** @var API API handler instance */
	protected $api;


	/**
	 * Constructs the gateway.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			\SkyVerge\WooCommerce\Bambora\Plugin::LEGACY_GATEWAY_ID,
			wc_bambora(),
			array(
				'method_title'       => __( 'Bambora (Legacy)', 'woocommerce-gateway-bambora' ),
				'method_description' => __( 'Allow customers to securely pay using their credit cards with Bambora.', 'woocommerce-gateway-bambora' ),
				'supports'           => array(
					self::FEATURE_PRODUCTS,
					self::FEATURE_CARD_TYPES,
					self::FEATURE_PAYMENT_FORM,
					self::FEATURE_CREDIT_CARD_AUTHORIZATION,
					self::FEATURE_CREDIT_CARD_CHARGE,
					self::FEATURE_CREDIT_CARD_CHARGE_VIRTUAL,
					self::FEATURE_DETAILED_CUSTOMER_DECLINE_MESSAGES,
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
			'hash_key' => array(
				'title'       => __( 'Hash Key', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your hash key, as configured in your Bambora account', 'woocommerce-gateway-bambora' ),
				'type'        => 'text',
				'class'       => 'environment-field production-field',
			),
			'test_hash_key' => array(
				'title'       => __( 'Hash Key', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your hash key, as configured in your Bambora account', 'woocommerce-gateway-bambora' ),
				'type'        => 'text',
				'class'       => 'environment-field test-field',
			),
			'hash_algorithm' => array(
				'title'       => __( 'Hash Algorithm', 'woocommerce-gateway-bambora' ),
				'description' => __( 'Your hash algorithm, as configured in your Bambora account', 'woocommerce-gateway-bambora' ),
				'type'        => 'select',
				'options'     => array(
					'md5'  => 'MD5',
					'sha1' => 'SHA-1',
				),
				'default' => 'md5',
			),
		);
	}


	/**
	 * Initializes the settings form fields.
	 *
	 * This is overridden to apply a legacy filter for backwards compatibility.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::init_form_fields()
	 *
	 * @since 2.0.0
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		/**
		 * Filters the Bambora Legacy gateway settings fields.
		 *
		 * @since 1.7.2
		 * @deprecated 2.0.0
		 *
		 * @param array $form_fields admin settings fields
		 * @param Gateway $gateway gateway object
		 */
		$this->form_fields = apply_filters( 'wc_payment_gateway_beanstream_credit_card_form_fields', $this->form_fields, $this );
	}


	/**
	 * Gets the payment method field defaults.
	 *
	 * Adds a default CC number in the test environment.
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_payment_method_defaults()
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_payment_method_defaults() {

		$defaults = parent::get_payment_method_defaults();

		if ( $this->is_test_environment() ) {
			$defaults['account-number'] = '4030000010001234';
		}

		return $defaults;
	}


	/**
	 * Gets a user's customer ID.
	 *
	 * Bambora Legacy does not support customer IDs.
	 *
	 * @since 2.0.0
	 *
	 * @param int $user_id WordPress user ID
	 * @param array $args request args - @see Framework\SV_WC_Payment_Gateway::get_customer_id()
	 * @return false
	 */
	public function get_customer_id( $user_id, $args = array() ) {

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
			$this->api = new API( $this );
		}

		return $this->api;
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
	public function get_hash_key( $environment_id = null ) {

		if ( null === $environment_id ) {
			$environment_id = $this->get_environment();
		}

		return self::ENVIRONMENT_PRODUCTION === $environment_id ? $this->hash_key : $this->test_hash_key;
	}


	/**
	 * Gets the hash algorithm.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_hash_algorithm() {

		return $this->hash_algorithm;
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


}
