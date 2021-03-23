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

namespace SkyVerge\WooCommerce\Bambora\API\Response;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The Bambora API profile response class.
 *
 * @since 2.0.0
 */
class Profile extends \SkyVerge\WooCommerce\Bambora\API\Response implements Framework\SV_WC_Payment_Gateway_API_Customer_Response, Framework\SV_WC_Payment_Gateway_API_Get_Tokenized_Payment_Methods_Response, Framework\SV_WC_Payment_Gateway_API_Create_Payment_Token_Response {


	/** @var Framework\SV_WC_Payment_Gateway_Payment_Token token object */
	protected $token;


	/**
	 * Gets the customer ID for this profile.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_customer_id() {

		return $this->customer_code;
	}


	/**
	 * Gets the payment methods.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_payment_tokens() {

		$tokens = array();
		$cards  = $this->card;

		if ( ! empty( $cards ) && is_array( $cards ) ) {

			foreach ( $cards as $card ) {

				$tokens[ $card->card_id ] = new Framework\SV_WC_Payment_Gateway_Payment_Token( $card->card_id, array(
					'type'      => 'credit_card',
					'last_four' => substr( $card->number, -4 ),
					'exp_month' => $card->expiry_month,
					'exp_year'  => "20{$card->expiry_year}",
					'card_type' => $this->get_card_type( $card->card_type ),
					'default'   => 'DEF' === $card->function,
				) );
			}
		}

		return $tokens;
	}


	/**
	 * Gets the payment token (card ID) associated with this request, if any.
	 *
	 * The Bambora API doesn't return a card ID when adding a new method, so we
	 * have to set this later so the gateway knows the new "token" to store.
	 *
	 * @since 2.0.0
	 *
	 * @return Framework\SV_WC_Payment_Gateway_Payment_Token|null
	 */
	public function get_payment_token() {

		return $this->token;
	}


	/**
	 * Sets a payment token object for this response.
	 *
	 * @since 2.0.0
	 *
	 * @param Framework\SV_WC_Payment_Gateway_Payment_Token $token payment token object
	 */
	public function set_payment_token( Framework\SV_WC_Payment_Gateway_Payment_Token $token ) {

		$this->token = $token;
	}


	/**
	 * Determines if the request was approved.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		return 1 === (int) $this->get_status_code();
	}


	/**
	 * Gets the status code.
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_status_code() {

		return $this->code;
	}


	/**
	 * Gets a normalized card type string from the codes returned by the API.
	 *
	 * @since 2.0.0
	 *
	 * @param string $api_type card type
	 * @return string
	 */
	protected function get_card_type( $api_type ) {

		$types = array(
			'AM' => Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_AMEX,
			'DI' => Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_DINERSCLUB,
			'JB' => Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_JCB,
			'MC' => Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_MASTERCARD,
			'NN' => Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_DISCOVER,
			'VI' => Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_VISA,
		);

		return ! empty( $types[ $api_type ] ) ? $types[ $api_type ] : $api_type;
	}


	/**
	 * Gets the payment type.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_payment_type() {

		return Framework\SV_WC_Payment_Gateway::PAYMENT_TYPE_CREDIT_CARD;
	}


	/** No-op methods *********************************************************/


	/**
	 * Gets the customer-friendly error message.
	 *
	 * @since 2.0.0
	 */
	public function get_user_message() {}


	/**
	 * No-op: profile responses don't have an ID.
	 *
	 * @since 2.0.0
	 */
	public function get_transaction_id() {}


}
