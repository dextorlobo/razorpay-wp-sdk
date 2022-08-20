<?php
/**
 * Main plugin class.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\RazorpayWpSdk;
use Imarun\RazorpayWpSdk\Callbacks;
use Imarun\RazorpayWpSdk\Admin\Settings;
use Imarun\RazorpayWpSdk\Admin\Orderslist;
use Imarun\RazorpayWpSdk\Orders;

/**
 * The core plugin class.
 *
 * @since   0.1.0
 * @package razorpay-wp-sdk
 */
class Plugin {

	const API_NAMESPACE = 'razorpaywp';

	private   $callbacks;

	protected $api_key ='12345';

	public function __construct() {
		$this->settings   = new Settings();
		$this->orderslist = new Orderslist();
		$this->callbacks  = new Callbacks();
		$this->run();

		/**
		 * Load hooks after setup theme.
		 */
		add_action( 'after_setup_theme', array( new Orders, 'register_orders_post_type' ) );
		add_action( 'after_get_all_payments_callback', array( new Orders, 'insert_all_payments_in_wp' ), 10, 2 );
	}

	/**
	 * Initialise the plugin.
	 */
	public function run() {
		$this->register_routes();
	}

	/**
	 * Get the plugin routes.
	 *
	 * @return array
	 */
	private function routes(): array {
		return array(
			'/payments' => array(
				'methods' => 'GET',
				'callback' => array( new Callbacks, 'get_all_payments_callback' ),
				'permission_callback' => array( $this, 'check_access' ),
			),
			'/payments/(?P<id>\S+)' => array(
				'methods' => 'GET',
				'callback' => array( new Callbacks, 'get_single_payment_callback' ),
				'permission_callback' => array( $this, 'check_access' ),
			),
			'/insert-payments' => array(
				'methods' => 'GET',
				'callback' => array( new Callbacks, 'insert_payments_callback' ),
				'permission_callback' => array( $this, 'check_access' ),
			),
			'/payments-webhook' => array(
				'methods' => 'POST',
				'callback' => array( new Callbacks, 'payment_webhook_process_callback' ),
				'permission_callback' => '__return_true',
			),
		);
	}

	/**
	 * Register plugin routes.
	 */
	private function register_routes() {
		add_action(
			'rest_api_init',
			function () {
				foreach ( $this->routes() as $route => $args ) {
					register_rest_route( self::API_NAMESPACE, $route, $args );
				}
			} 
		);
	}

	/**
	 * Check access.
	 */
	public function check_access( \WP_REST_Request $request ) {
		$key  = $request->get_header( 'api-secret-key' );
		if($key == $this->api_key){
			return true;
		}
		return false;
	}
}
