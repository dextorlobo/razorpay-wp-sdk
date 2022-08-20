<?php
/**
 * The plugin bootstrap file.
 *
 * @since 0.1.0
 * @package razorpay-wp-sdk
 *
 * @wordpress-plugin
 * Plugin Name:       Razorpay WP SDK
 * Description:       WordPress plugin which sets up Razorpay credentials and provides an Razorpay SDK instance to utilise.
 * Version:           0.1.0
 * Author:            Arun Sharma
 * Author URI:        https://www.imarun.me/
 * Text Domain:       razorpay-wp-sdk
 */

declare( strict_types = 1 );

use Razorpay\Api\Api;
use Imarun\RazorpayWpSdk\Api\Provider;
use Imarun\RazorpayWpSdk\Endpoints\Payments;
use Imarun\RazorpayWpSdk\Exception\InvalidArgumentException;
use Imarun\RazorpayWpSdk\Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * API and Plugin version constants.
 */
define( 'RAZORPAY_WP_PLUGIN_VERSION', '0.1.0' );

include_once __DIR__ . '/vendor/autoload.php';

$plugin = new Plugin();

/**
 * Get Razorpay Instace.
 *
 * @return Api|WP_Error
 */
function get_wp_razorpay_instace() {
	try {
		$razorpayWebhookSecret_option = get_option('wp_rzp_options');
		if( ! empty( $razorpayWebhookSecret_option ) && isset( $razorpayWebhookSecret_option['wp_rzp_field_key_id'] ) ) {
			$key = $razorpayWebhookSecret_option['wp_rzp_field_key_id'];
		}else {
			$key = '';
		}

		if( ! empty( $razorpayWebhookSecret_option ) && isset( $razorpayWebhookSecret_option['wp_rzp_field_secret_key_id'] ) ) {
			$secret = $razorpayWebhookSecret_option['wp_rzp_field_secret_key_id'];
		}else {
			$secret = '';
		}

		$provider = new Provider( $key, $secret );
		$instance = $provider->get_instance();

		return $instance;
	} catch ( InvalidArgumentException $e ) {
		return new \WP_Error( $e->get_code(), sprintf( 'wp_razorpay_instace: %s', $e->getMessage() ), array( 'status' => $e->get_code() ) );
	}
}
