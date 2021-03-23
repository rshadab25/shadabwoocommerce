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
 * The API response message helper class.
 *
 * @see Framework\SV_WC_Payment_Gateway_API_Response_Message_Helper
 *
 * @since 2.0.0
 */
class Message_Helper extends Framework\SV_WC_Payment_Gateway_API_Response_Message_Helper {


	/** @var array known message IDs */
	protected $message_ids = array(

		// card number
		'card_number_invalid' => array( '52', '351', '1026' ),
		'card_inactive'       => array( '1039' ),

		// CSC & AVS
		'csc_invalid'  => array( '568', '704', '755' ),
		'csc_mismatch' => array( '821' ),
		'avs_mismatch' => array( '822' ),

		// expiration
		'card_expired'        => array( '72', '168', '524', '694', '1006', '1100' ),
		'card_expiry_invalid' => array( '14', '26', '148', '352', '397', '574', '710', '713', '1068' ),

		// general declines
		'card_type_not_accepted' => array( '312' ),
		'insufficient_funds'     => array( '1027', '1086' ),
		'card_declined'          => array( '7' ),
	);


	/**
	 * Gets the message from a response status code.
	 *
	 * @since 2.0.0
	 *
	 * @param string $status_code a response status code
	 * @return string customer-friendly message
	 */
	public function get_message( $status_code ) {

		$message_id = 'error';

		foreach ( $this->message_ids as $error_id => $statuses ) {

			// if a status code was found, use its message ID
			if ( in_array( (string) $status_code, $statuses, true ) ) {
				$message_id = $error_id;
				break;
			}
		}

		return $this->get_user_message( $message_id );
	}


}
