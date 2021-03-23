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
 * The Bambora API base response class.
 *
 * @since 2.0.0
 */
class Response extends Framework\SV_WC_API_JSON_Response {


	/**
	 * Determines if the transaction was held.
	 *
	 * Bambora doesn't hold orders.
	 *
	 * @since 2.0.0
	 *
	 * @return false
	 */
	public function transaction_held() {

		return false;
	}


	/**
	 * Gets the response code if present.
	 *
	 * Some responses don't include this. It'll always be present in case of an
	 * error, but doesn't necessarily indicate one.
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */
	public function get_code() {

		return $this->code;
	}


	/**
	 * Gets the error category if present.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_error_category() {

		return $this->category;
	}


	/**
	 * Gets the response status message.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_status_message() {

		$message = $this->message;
		$details = $this->get_error_details();

		if ( is_array( $details ) ) {

			foreach ( $this->get_error_details() as $detail ) {
				$message .= is_string( $detail->message ) ? " {$detail->message}." : '';
			}
		}

		return $message;
	}


	/**
	 * Gets any error details if present.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_error_details() {

		return $this->details;
	}


}
