<?php
/**
 * Payments Class 
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Imarun\RazorpayWpSdk\Endpoints;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\Error;
use Imarun\RazorpayWpSdk\Orders;

/**
 * Class Payments
 *
 * @package Imarun\RazorpayWpSdk\Api
 * @since 0.1.0
 */
class Payments {

	/**
	 * Instance.
	 *
	 * @var Api
	 */
	public $instance;

	/**
	 * Payments constructor.
	 *
	 * @since   0.1.0
	 *
	 * @param Api $instance Instance.
	 */
	public function __construct( Api $instance ) {
		$this->instance = $instance;
	}

	/**
	 * Get Payments.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function get_payments( $args = array() ) {
		try {
			return $this->instance->payment->all( $args );
		} catch ( Error $e ) {
			error_log( sprintf( 'Payments Response Error: %s', $e->getMessage() ) );
			return new \WP_Error( $e->getCode(), sprintf( 'Payments Response Error: %s', $e->getMessage() ), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Get Payment.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function get_payment( $payment_id ) {
		try {
			return $this->instance->payment->fetch( $payment_id );
		} catch ( Error $e ) {
			error_log( sprintf( 'Payments Response Error: %s', $e->getMessage() ) );
			return new \WP_Error( $e->getCode(), sprintf( 'Payments Response Error: %s', $e->getMessage() ), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Payment captured.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function payment_captured( Orders $orders, array $data ) {
		$orders_res = $orders->insert_order( $data );
		if( is_wp_error( $orders_res ) ){
			error_log( json_encode( $orders_res ) );
			return $orders_res;
		}

		do_action( 'after_payment_capture_webhook', $data );

		return $orders_res;
	}
}
