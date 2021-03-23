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

namespace SkyVerge\WooCommerce\Bambora\API\Request;

use SkyVerge\WooCommerce\Bambora\Gateway\Credit_Card;
use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * The Bambora API payment request class.
 *
 * @since 2.0.0
 */
class Payment extends \SkyVerge\WooCommerce\Bambora\API\Request {


	/** @var string API URL path */
	protected $path = '/payments';

	/** @var string payment method slug */
	protected $payment_method;


	/**
	 * Sets the credit card charge transaction data.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function set_charge_data( \WC_Order $order ) {

		$this->set_payment_data( $order, true );
	}


	/**
	 * Sets the credit card authorization transaction data.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @throws Framework\SV_WC_API_Exception
	 */
	public function set_authorization_data( \WC_Order $order ) {

		$this->set_payment_data( $order, false );
	}


	/**
	 * Sets the necessary transaction data.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param bool $complete whether the transaction should be completed/captured
	 * @throws Framework\SV_WC_API_Exception
	 */
	protected function set_payment_data( \WC_Order $order, $complete = true ) {

		$payment_method_data = $this->get_payment_method_data( $order );

		// ensure the request class has a payment method type defined
		if ( empty( $this->payment_method ) ) {
			throw new Framework\SV_WC_API_Exception( 'No payment method defined' );
		}

		$data = array(
			'order_number'        => Framework\SV_WC_Helper::str_truncate( $order->get_order_number(), 30, '' ),
			'amount'              => $order->payment_total,
			'payment_method'      => $this->payment_method,
			'customer_ip'         => Framework\SV_WC_Helper::str_truncate( $order->get_customer_ip_address( 'edit' ), 30, '' ),
			'comments'            => Framework\SV_WC_Helper::str_truncate( $order->get_customer_note( 'edit' ), 30 ),
			'billing'             => $this->get_billing_data( $order ),
			'shipping'            => $this->get_shipping_data( $order ),
			'recurring_payment'   => ! empty( $order->payment->recurring ),
			$this->payment_method => $payment_method_data,
		);

		$card_on_file = $this->get_card_on_file_data( $order, $complete ); // this is set for both tokenized and non-tokenized transactions

		if ( ! empty( $card_on_file ) ) {
			$data['card_on_file'] = $card_on_file;
		}

		$data[ $this->payment_method ]['complete'] = (bool) $complete;

		// remove empty params
		foreach ( $data as $key => $value ) {

			if ( empty( $value ) && false !== $value ) {
				unset( $data[ $key ] );
			}
		}

		$this->data = $data;
	}


	/**
	 * Gets the payment method data.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_payment_method_data( \WC_Order $order ) {

		$data = array();

		if ( ! empty( $order->payment->token ) ) {

			$this->payment_method = 'payment_profile';

			$data = array(
				'customer_code' => $order->customer_id,
				'card_id'       => $order->payment->token,
			);

		} elseif ( ! empty( $order->payment->js_token ) ) {

			$this->payment_method = 'token';

			$data = array(
				'code' => $order->payment->js_token,
				'name' => Framework\SV_WC_Helper::str_truncate( $order->get_formatted_billing_full_name(), 64, '' ),
			);
		}

		return $data;
	}


	/**
	 * Gets the card-on-file data for the saved method.
	 *
	 * @see Credit_Card::get_order()
	 * @see Credit_Card::add_payment_gateway_transaction_data()
	 *
	 * @since 2.0.8
	 *
	 * @param \WC_Order $order order object
	 * @param bool $is_purchase whether the transaction is a full purchase, or auth-only
	 * @return array
	 */
	protected function get_card_on_file_data( \WC_Order $order, $is_purchase ) {

		$data = [];

		// if a tokenized transaction
		if ( ! empty( $order->payment->token ) ) {

			// if dealing with a subscription, set the appropriate recurring type
			if ( ! empty( $order->payment->subscriptions ) ) {

				$subscription = $type = null;

				// we can only use data from  the first subscription in the order
				foreach ( $order->payment->subscriptions as $subscription_data ) {

					if ( is_object( $subscription_data ) && ! empty( $subscription_data->id ) ) {

						$subscription = $subscription_data;
						break;
					}
				}

				if ( $subscription ) {

					if ( $subscription->is_renewal ) {

						// the API does not allow this for auth-only transactions
						if ( $is_purchase ) {
							$type = $subscription->is_installment ? 'subsequent_installment' : 'subsequent_recurring';
						}

					} else {

						$type = $subscription->is_installment ? 'first_installment' : 'first_recurring';
					}

					if ( $type ) {

						$data['type'] = $type;

						if ( isset( $subscription->series_id ) ) {

							$data['series_id'] = $subscription->series_id;
						}
					}
				}
			}

			// otherwise, this is a customer-initiated saved payment or pre-order
			if ( empty( $subscription ) ) {
				$data['type'] = 'subsequent_customer_initiated';
			}

		// otherwise, a regular card transaction
		} else {

			$data['type'] = 'not_card_on_file';
		}

		return $data;
	}


	/**
	 * Sets the data for transaction capture/completion.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_capture_data( \WC_Order $order ) {

		$this->path .= "/{$order->capture->trans_id}/completions";

		$this->data = array(
			'amount' => $order->capture->amount,
		);
	}


	/**
	 * Sets the data for transaction refund.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_refund_data( \WC_Order $order ) {

		$this->path .= "/{$order->refund->trans_id}/returns";
		$this->data  = $this->get_void_refund_data( $order );
	}


	/**
	 * Sets the data for transaction refund.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function set_void_data( \WC_Order $order ) {

		$this->path .= "/{$order->refund->trans_id}/void";
		$this->data  = $this->get_void_refund_data( $order );
	}


	/**
	 * Gets the data for a void or refund.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return array
	 */
	protected function get_void_refund_data( \WC_Order $order ) {

		return array(
			'order_number' => Framework\SV_WC_Helper::str_truncate( $order->get_order_number(), 30, '' ),
			'amount'       => $order->refund->amount,
		);
	}


}
