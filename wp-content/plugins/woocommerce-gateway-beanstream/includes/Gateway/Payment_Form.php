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

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * Handles the custom Bambora payment form.
 *
 * This overrides the framework's implementation to support Bambora Custom Checkout,
 * which uses iframes for the card inputs like Braintree.
 *
 * @since 2.0.0
 */
class Payment_Form extends Framework\SV_WC_Payment_Gateway_Payment_Form {


	/** @var string JavaScript handler base class name */
	protected $js_handler_base_class_name = 'WC_Bambora_Payment_Form_Handler';


	/**
	 * Gets the JS handler class name.
	 *
	 * @since 2.2.8
	 *
	 * @return string
	 */
	protected function get_js_handler_class_name() {

		return $this->js_handler_base_class_name;
	}


	/**
	 * Renders the payment form description.
	 *
	 * Overridden to add the test card numbers for easier sandbox testing.
	 * @see Framework\SV_WC_Payment_Gateway_Payment_Form::render_payment_form_description()
	 *
	 * @since 2.0.0
	 */
	public function render_payment_form_description() {

		parent::render_payment_form_description();

		if ( $this->get_gateway()->is_test_environment() ) : ?>

			<p><?php esc_html_e( 'Test card numbers:', 'woocommerce-gateway-bambora' ); ?></p>

			<ul class="wc-bambora-test-card-numbers">
				<li><code>4030000010001234</code> - <?php esc_html_e( 'Approved', 'woocommerce-gateway-bambora' ); ?></li>
				<li><code>4003050500040005</code> - <?php esc_html_e( 'Declined', 'woocommerce-gateway-bambora' ); ?></li>
			</ul>

		<?php endif;
	}


	/**
	 * Renders the payment fields.
	 *
	 * Overridden to add the hidden fields for Custom Checkout tokenization.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Payment_Form::render_payment_fields()
	 *
	 * @since 2.0.0
	 */
	public function render_payment_fields() {

		parent::render_payment_fields();

		$hidden_fields = array(
			'js-token',
			'account-number', // this will only be the last four, but name it so it's fully handled by the framework when setting order notes/meta
			'exp-month',
			'exp-year',
			'card-type',
		);

		foreach ( $hidden_fields as $field ) {
			echo '<input type="hidden" name="wc-' . $this->get_gateway()->get_id_dasherized() . '-' . sanitize_html_class( $field ) . '" />';
		}
	}


	/**
	 * Renders the payment fields.
	 *
	 * Overridden to output empty divs for each field instead of actual inputs.
	 * Bambora Custom Checkout uses these to attach iframe inputs. The divs are
	 * styled like the inputs would be, and the transparent iframes sit inside.
	 *
	 * @since 2.0.0
	 *
	 * @param array $field payment field params
	 */
	public function render_payment_field( $field ) {

		?>
		<div class="form-row <?php echo implode( ' ', array_map( 'sanitize_html_class', $field['class'] ) ); ?>">
			<label for="<?php echo esc_attr( $field['id'] ) . '-hosted'; ?>"><?php echo esc_html( $field['label'] ); if ( $field['required'] ) : ?><abbr class="required" title="required">&nbsp;*</abbr><?php endif; ?></label>
			<div id="<?php echo esc_attr( $field['id'] ) . '-hosted'; ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $field['input_class'] ) ); ?>" data-placeholder="<?php echo isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : ''; ?>"></div>
		</div>
		<?php
	}


	/**
	 * Gets the JS args for the payment form handler.
	 *
	 * @since 2.2.7
	 *
	 * @return array
	 */
	protected function get_js_handler_args() {

		return [
			'id'         => $this->get_gateway()->get_id(),
			'slug'       => $this->get_gateway()->get_id_dasherized(),
			'debug'      => $this->get_gateway()->debug_log(),
			'card_types' => $this->get_available_card_types(),
			'styles'     => $this->get_field_styles(),
		];
	}


	/**
	 * Gets the card types made available at checkout.
	 *
	 * This is configured in the plugin settings. Normally that setting doesn't
	 * affect which card types are allow through, but Bambora Custom Checkout
	 * allows us to pass card types which is used during form validation and will
	 * throw an error if an unsupported type is used.
	 *
	 * Note that we adjust at least one card type slug to match what their JS
	 * expects.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_available_card_types() {

		$types = array_map( array( '\\SkyVerge\\WooCommerce\\PluginFramework\\v5_8_1\\SV_WC_Payment_Gateway_Helper', 'normalize_card_type' ), $this->get_gateway()->get_card_types() );

		$types = str_replace( Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_DINERSCLUB, 'diners', $types );

		return $types;
	}


	/**
	 * Gets the payment field styles.
	 *
	 * Their JS allows us to specify styles to render on each iframe field. This
	 * sets some common base styles for each, and allows filtering to further match
	 * other theme-specific styles.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_field_styles() {

		$base_styles = array(
			'base' => array(
				'fontSize' => '1.3em',
			),
		);

		$field_styles = array(
			'card_number' => $base_styles,
			'expiry'      => $base_styles,
			'cvv'         => $base_styles,
		);

		/**
		 * Filters the Bambora payment field styles.
		 *
		 * @since 2.0.0
		 *
		 * @param array $styles payment field styles
		 * @param Payment_Form $form_handler payment form handler
		 */
		return apply_filters( 'wc_' . $this->get_gateway()->get_id() . '_payment_field_styles', $field_styles, $this );
	}


}
