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
 * The Bambora Legacy API response class.
 *
 * @since 2.0.0
 */
class Response implements Framework\SV_WC_Payment_Gateway_API_Authorization_Response {


	/** @var array response data */
	protected $data = array();


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 *
	 * @param string $raw_data URL query string with the response data
	 */
	public function __construct( $raw_data ) {

		parse_str( $raw_data, $data );

		$this->data = $data;
	}


	/**
	 * Determines if the transaction was approved.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		return isset( $this->data['trnApproved'] ) && 1 === (int) $this->data['trnApproved'];
	}


	/**
	 * Determines if the transaction was held.
	 *
	 * @since 2.0.0
	 *
	 * @return false
	 */
	public function transaction_held() {

		return false; // the legacy API doesn't hold orders
	}


	/**
	 * Gets the transaction result code.
	 *
	 * @since 2.0.0
	 *
	 * @return null
	 */
	public function get_status_code() {

		return isset( $this->data['messageId'] ) ? $this->data['messageId'] : '';}


	/**
	 * Gets the transaction result message.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_status_message() {

		$message = '';

		if ( isset( $this->data['messageText'] ) ) {

			$message = str_replace( '<br><LI>', '. ', $this->data['messageText'] );
			$message = wp_strip_all_tags( $message );
		}

		return $message;
	}


	/**
	 * Gets the transaction ID.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_transaction_id() {

		return isset( $this->data['trnId'] ) ? $this->data['trnId'] : '';
	}


	/**
	 * Gets the transaction authorization code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_authorization_code() {

		return isset( $this->data['authCode'] ) ? $this->data['authCode'] : '';
	}


	/**
	 * Gets the AVS result code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_avs_result() {

		return isset( $this->data['avsId'] ) ? $this->data['avsId'] : '';
	}


	/**
	 * Gets the CSC validation result code
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_csc_result() {

		return isset( $this->data['cvdId'] ) ? $this->data['cvdId'] : '';
	}


	/**
	 * Gets the card type.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_card_type() {

		$card_types = array(
			'VI' => 'Visa',
			'PV' => 'Visa Debit',
			'MC' => 'MasterCard',
			'AM' => 'American Express',
			'NN' => 'Discover',
			'DI' => 'Diners',
			'JB' => 'JCB',
			'IO' => 'INTERAC Online',
			'ET' => 'Direct Debit/Direct Payments/ACH',
		);

		return isset( $this->data['cardType'] ) && isset( $card_types[ $this->data['cardType'] ] ) ? $card_types[ $this->data['cardType'] ] : '';
	}


	/**
	 * Gets the payment type: 'credit-card', 'echeck', etc...
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_payment_type() {

		return Framework\SV_WC_Payment_Gateway::PAYMENT_TYPE_CREDIT_CARD;
	}


	/**
	 * Gets the user-friendly message for detailed declined messages.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_user_message() {

		$helper = new \SkyVerge\WooCommerce\Bambora\API\Message_Helper();

		return $helper->get_message( $this->get_status_code() );
	}


	/**
	 * Gets the returned hash value for validation.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_hash_value() {

		return isset( $this->data['hashValue'] ) ? $this->data['hashValue'] : '';
	}


	/**
	 * Gets the string representation of the request data.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function to_string() {

		return print_r( $this->data, true );
	}


	/**
	 * Gets the string representation of the response with any sensitive data
	 * masked or removed.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function to_string_safe() {

		$string = $this->to_string();

		// mask the hash value
		if ( ! empty( $this->data['hashValue'] ) ) {
			$string = str_replace( $this->data['hashValue'], str_repeat( '*', strlen( $this->data['hashValue'] ) ), $string );
		}

		return $string;
	}


	/** No-op methods *********************************************************/


	/**
	 * No-op: the Bambora Legacy API does not return a CSC result.
	 *
	 * @since 2.0.0
	 */
	public function csc_match() {}


}
