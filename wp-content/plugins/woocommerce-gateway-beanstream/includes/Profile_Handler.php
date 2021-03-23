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
 * Bambora payment profile handler.
 *
 * @method Gateway\Credit_Card get_gateway()
 */
class Profile_Handler extends Framework\SV_WC_Payment_Gateway_Payment_Tokens_Handler {


	/**
	 * Gets a user's token.
	 *
	 * Overridden to reset the token's billing hash to the single hash stored for the user, since Bambora only has one
	 * address for the profile, not for the card.
	 *
	 * @since 2.0.6
	 *
	 * @param int $user_id WordPress user ID
	 * @param string $token token ID
	 * @param string|null $environment_id desired environment ID
	 * @return Framework\SV_WC_Payment_Gateway_Payment_Token|null
	 */
	public function get_token( $user_id, $token, $environment_id = null ) {

		$payment_method = parent::get_token( $user_id, $token, $environment_id );

		if ( $payment_method instanceof Framework\SV_WC_Payment_Gateway_Payment_Token ) {
			$payment_method->set_billing_hash( $this->get_billing_hash( $user_id, $environment_id ) );
		}

		return $payment_method;
	}


	/**
	 * Updates a token's stored data.
	 *
	 * @since 2.0.6
	 *
	 * @param int $user_id WordPress user ID
	 * @param Framework\SV_WC_Payment_Gateway_Payment_Token $token token object
	 * @param string|null $environment_id desired environment ID
	 *
	 * @return int|string
	 */
	public function update_token( $user_id, $token, $environment_id = null ) {

		// store the overall user profile hash any time a token is updated
		$this->store_billing_hash( $user_id, $token->get_billing_hash(), $environment_id );

		return parent::update_token( $user_id, $token, $environment_id );
	}


	/**
	 * Stores a billing hash for the given user.
	 *
	 * @since 2.0.6
	 *
	 * @param int $user_id WordPress user ID
	 * @param string $hash hash to set
	 * @param string|null $environment_id desired environment ID
	 * @return bool|int
	 */
	protected function store_billing_hash( $user_id, $hash, $environment_id = null ) {

		return update_user_meta( $user_id, $this->get_billing_hash_user_meta_key( $environment_id ), $hash );
	}


	/**
	 * Gets the billing hash stored for the given user.
	 *
	 * @since 2.0.6
	 *
	 * @param int $user_id WordPress user ID
	 * @param string|null $environment_id desired environment ID
	 * @return string
	 */
	protected function get_billing_hash( $user_id, $environment_id = null ) {

		return get_user_meta( $user_id, $this->get_billing_hash_user_meta_key( $environment_id ), true );
	}


	/**
	 * Gets the user meta key used for storing a user's billing hash.
	 *
	 * @since 2.0.6
	 *
	 * @param string|null $environment desired environment ID
	 * @return string
	 */
	protected function get_billing_hash_user_meta_key( $environment = null ) {

		if ( ! $environment ) {
			$environment = $this->get_gateway()->get_environment();
		}

		return 'wc_' . $this->get_gateway()->get_plugin()->get_id() . '_billing_hash' . ( ! $this->get_gateway()->is_production_environment( $environment ) ? '_' . $environment : '' );
	}


}
