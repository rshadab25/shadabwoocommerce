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
class Payment extends \SkyVerge\WooCommerce\Bambora\API\Response implements Framework\SV_WC_Payment_Gateway_API_Authorization_Response {


	/**
	 * Determines if the payment was approved.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		return 1 === (int) $this->approved;
	}


	/**
	 * Gets the payment authorization code.
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_authorization_code() {

		return $this->auth_code;
	}


	/**
	 * Gets the payment transaction ID.
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_transaction_id() {

		return $this->id;
	}


	/**
	 * Determines if the AVS was a match.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function avs_match() {

		return (bool) $this->avs_result;
	}


	/**
	 * Determines if AVS was processed.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function avs_processed() {

		return (bool) $this->processed;
	}


	/**
	 * Gets the payment AVS result code.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_avs_result() {

		return isset( $this->avs ) ? $this->avs->id : '';
	}


	/**
	 * Gets the payment CSC validation result.
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_csc_result() {

		return $this->cvd_result;
	}


	/**
	 * Determines if the CSC was a match.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function csc_match() {

		return (bool) $this->cvd_result;
	}


	/**
	 * Gets the payment status code.
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_status_code() {

		return $this->message_id;
	}


	/**
	 * Gets the card-on-file series ID, if any.
	 *
	 * @since 2.0.8
	 *
	 * @return string
	 */
	public function get_series_id() {

		$card_on_file = $this->card_on_file;

		return ! empty( $card_on_file->series_id ) ? $card_on_file->series_id : '';
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


	/**
	 * Gets a customer-friendly error message if available.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_user_message() {

		$helper = new \SkyVerge\WooCommerce\Bambora\API\Message_Helper();

		return $helper->get_message( $this->get_status_code() );
	}


}
