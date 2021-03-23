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
 * @package   WooCommerceBambora/Plugin
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2020, SkyVerge, Inc. (info@skyverge.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Bambora;

use SkyVerge\WooCommerce\PluginFramework\v5_8_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * WooCommerce Bambora Gateway main plugin class.
 *
 * @since 2.0.0
 */
class Plugin extends Framework\SV_WC_Payment_Gateway_Plugin {


	/** @var Plugin single instance of this plugin */
	protected static $instance;


	/** string version number */
	const VERSION = '2.3.0';

	/** string the plugin ID */
	const PLUGIN_ID = 'bambora';

	/** string credit card gateway class name */
	const CREDIT_CARD_GATEWAY_CLASS_NAME = '\\SkyVerge\\WooCommerce\\Bambora\\Gateway\\Credit_Card';

	/** string credit card gateway id */
	const CREDIT_CARD_GATEWAY_ID = 'bambora_credit_card';

	/** string legacy (Beanstream) gateway class name */
	const LEGACY_GATEWAY_CLASS_NAME = '\\SkyVerge\\WooCommerce\\Bambora\\Legacy\\Gateway';

	/** string legacy (Beanstream) gateway ID */
	const LEGACY_GATEWAY_ID = 'beanstream';


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'text_domain'  => 'woocommerce-gateway-bambora',
				'gateways'     => $this->get_active_gateways(),
				'dependencies' => $this->get_active_dependencies(),
				'supports'     => $this->get_active_features(),
				'require_ssl'  => true,
			)
		);

		// handle toggling legacy integration
		add_action( 'admin_action_wc_' . $this->get_id() . '_toggle_legacy', array( $this, 'toggle_legacy' ) );
	}


	/**
	 * Gets the currently active gateways.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_active_gateways() {

		if ( $this->is_legacy_active() ) {

			$gateways = array(
				self::LEGACY_GATEWAY_ID => self::LEGACY_GATEWAY_CLASS_NAME,
			);

		} else {

			$gateways = array(
				self::CREDIT_CARD_GATEWAY_ID => self::CREDIT_CARD_GATEWAY_CLASS_NAME,
			);
		}

		return $gateways;
	}


	/**
	 * Gets the supported features for the currently active gateways.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_active_features() {

		$features = array();

		if ( ! $this->is_legacy_active() ) {

			$features = array(
				self::FEATURE_CAPTURE_CHARGE,
				self::FEATURE_CUSTOMER_ID,
				self::FEATURE_MY_PAYMENT_METHODS,
			);
		}

		return $features;
	}


	/**
	 * Gets the dependencies for the currently active gateways.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_active_dependencies() {

		return ! $this->is_legacy_active() ? array( 'php_extensions' => 'json' ) : array();
	}


	/**
	 * Determines if the legacy integration is active.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function is_legacy_active() {

		// note: Framework\SV_WC_Plugin::get_id() cannot be used as it's not set early enough in some cases
		return 'yes' === get_option( 'wc_' . self::PLUGIN_ID . '_legacy_active', 'no' );
	}


	/**
	 * Gets the legacy deprecated hooks and defines their replacements.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_deprecated_hooks() {

		return array(
			'wc_payment_gateway_beanstream_credit_card_form_fields' => array(
				'version' => '2.0.0',
				'removed' => false,
			),
			'wc_gateway_beanstream_merchantid' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'map'         => true,
				'replacement' => 'wc_beanstream_legacy_merchant_id',
			),
			'wc_beanstream_credit_card_legacy_hashkey' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'map'         => true,
				'replacement' => 'wc_beanstream_legacy_hash_key',
			),
		);
	}


	/**
	 * Gets the My Payment Methods handler instance.
	 *
	 * @since 2.2.8
	 *
	 * @return My_Payment_Methods
	 */
	protected function get_my_payment_methods_instance() {

		require_once( $this->get_plugin_path() . '/includes/My_Payment_Methods.php' );

		return new My_Payment_Methods( $this );
	}


	/** Admin methods *********************************************************/


	/**
	 * Adds a notice when gateways are switched.
	 *
	 * @see Framework\SV_WC_Plugin::add_admin_notices()
	 *
	 * @since 2.0.0
	 */
	public function add_admin_notices() {

		parent::add_admin_notices();

		if ( isset( $_GET['legacy_toggled'] ) ) {

			$message = $this->is_legacy_active() ? __( 'Bambora Legacy Gateway is now active.', 'woocommerce-gateway-bambora' ) : __( 'Bambora Gateway is now active.', 'woocommerce-gateway-bambora' );

			$this->get_admin_notice_handler()->add_admin_notice( $message, 'legacy-toggled', array( 'dismissible' => false ) );
		}
	}


	/**
	 * Gets the plugin action links.
	 *
	 * @since 2.0.0
	 *
	 * @param array $actions action names => anchor tags
	 * @return array
	 */
	public function plugin_action_links( $actions ) {

		$actions = parent::plugin_action_links( $actions );

		// if the legacy gateway is active, offer switching to the new gateway
		if ( $this->is_legacy_active() ) {

			$toggle_link_text = __( 'Use the Bambora Gateway', 'woocommerce-gateway-bambora' );
			$insert_after_key = 'configure_beanstream';

		// or allow switching to the Legacy gateway if it was previously configured
		// we only want to check that the option exists, regardless of value
		} elseif ( get_option( 'wc_' . self::PLUGIN_ID . '_legacy_active', false ) ) {

			$toggle_link_text = __( 'Use Bambora Legacy', 'woocommerce-gateway-bambora' );
			$insert_after_key = 'configure_bambora_credit_card';

		// otherwise for new installs, consider the latest gateway the only option
		} else {

			return $actions;
		}

		$url  = wp_nonce_url( add_query_arg( 'action', 'wc_' . $this->get_id() . '_toggle_legacy', 'admin.php' ), $this->get_file() );

		$toggle_link = array(
			'toggle_legacy' => sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', esc_url( $url ), esc_html( $toggle_link_text ) ),
		);

		return Framework\SV_WC_Helper::array_insert_after( $actions, $insert_after_key, $toggle_link );
	}


	/**
	 * Handles toggling the legacy integration.
	 *
	 * @since 2.0.0
	 */
	public function toggle_legacy() {

		// security check
		if ( ! wp_verify_nonce( $_GET['_wpnonce'], $this->get_file() ) || ! current_user_can( 'manage_woocommerce' ) ) {
			wp_redirect( wp_get_referer() );
			exit;
		}

		update_option( 'wc_' . self::PLUGIN_ID . '_legacy_active', $this->is_legacy_active() ? 'no' : 'yes' );

		$return_url = add_query_arg( array( 'legacy_toggled' => 1 ), 'plugins.php' );

		// back from whence we came
		wp_redirect( $return_url );
		exit;
	}


	/** Helper methods ******************************************************/


	/**
	 * Gets the main Bambora instance.
	 *
	 * Ensures only one instance is/can be loaded.
	 *
	 * @see wc_bambora()
	 *
	 * @since 2.0.0
	 *
	 * @return Plugin
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Gets the plugin name.
	 *
	 * @see SV_WC_Payment_Gateway::get_plugin_name()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce Bambora Gateway', 'woocommerce-gateway-bambora' );
	}


	/**
	 * Determines if TLS v1.2 is required for API requests.
	 *
	 * @see Framework\SV_WC_Plugin::require_tls_1_2()
	 *
	 * @since 2.2.4
	 *
	 * @return true
	 */
	public function require_tls_1_2() {

		return true;
	}


	/**
	 * Gets the plugin documentation URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_documentation_url()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_documentation_url() {

		return 'https://docs.woocommerce.com/document/bambora/';
	}


	/**
	 * Gets the plugin support URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_support_url()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/marketplace-ticket-form/';
	}


	/**
	 * Gets the plugin sales page URL.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_sales_page_url() {

		return 'https://woocommerce.com/products/bambora/';
	}


	/**
	 * Returns __DIR__
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	protected function get_file() {

		return __DIR__;
	}


	/** Lifecycle methods ******************************************************/


	/**
	 * Initializes the lifecycle handler.
	 *
	 * @since 2.0.5
	 */
	protected function init_lifecycle_handler() {

		$this->lifecycle_handler = new Lifecycle( $this );
	}


}
