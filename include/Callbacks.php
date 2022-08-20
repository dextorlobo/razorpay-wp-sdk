<?php
/**
 * Callback Functions.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\RazorpayWpSdk;
use Razorpay\Api\Errors;
use Imarun\RazorpayWpSdk\Endpoints\Payments;
use Imarun\RazorpayWpSdk\Orders;
use WP_REST_Request;

class Callbacks {
	/**
	 * Event constants
	 */
	const PAYMENT_CAPATURED  = 'payment.captured';

	protected $eventsArray = [
		self::PAYMENT_CAPATURED,
	];

	/**
	 * Instance.
	 *
	 * @var Api
	 */
	private $instance;

	/**
	 * Instance.
	 *
	 * @var Payment
	 */
	private $payments;

	/**
	 * Orders.
	 *
	 * @var Orders
	 */
	private $orders;
	

	/**
	 * Callbacks constructor.
	 *
	 * @since   0.1.0
	 *
	 * @param Api $instance Instance.
	 */
	public function __construct() {

		$this->instance = get_wp_razorpay_instace();
		if( is_wp_error( $this->instance ) ){
			error_log(json_encode( $this->instance ));
			return $this->instance;
		}
		$this->payments = new Payments( $this->instance );
		$this->orders   = new Orders();
		
	}

	/**
	 * Get all payments callback.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function get_all_payments_callback( WP_REST_Request $request ) {

		if( is_wp_error( $this->instance ) ){
			error_log( 'get_all_payments_callback: '. json_encode( $this->$instance ));
			return $this->instance;
		}

		$args = [
			'count' => 10,
			'skip' => 0
		];

		$url_params = $request->get_query_params();

		if ( isset( $url_params['count'] ) && ! empty( $url_params['count'] ) ) {
			$args['count'] = $url_params['count'];
		}

		if ( isset( $url_params['skip'] ) && ! empty( $url_params['skip'] ) ) {
			$args['skip'] = $url_params['skip'];
		}

		$payments_res = $this->payments->get_payments( $args );

		if( is_wp_error( $payments_res ) ){
			error_log(json_encode( $payments_res ));

			return $payments_res;
		} else {
			error_log( json_encode( $payments_res->toArray() ) );

			return $payments_res->toArray();
		}
	}

	/**
	 * Get single payment callback.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function get_single_payment_callback( WP_REST_Request $request ) {

		if( is_wp_error( $this->instance ) ){
			error_log( 'get_single_payment_callback: '. json_encode( $this->$instance ));
			return $this->instance;
		}

		$url_params  = $request->get_url_params();
		if ( isset( $url_params['id'] ) ) {
			$payments_res = $this->payments->get_payment( $url_params['id'] );

			if( is_wp_error( $payments_res ) ){
				error_log(json_encode( $payments_res ));

				return $payments_res;
			} else {
				error_log( json_encode( $payments_res->toArray() ) );

				return $payments_res->toArray();
			}
		}
	}

	/**
	 * Payment webhook process callback.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function payment_webhook_process_callback() {

		if( is_wp_error( $this->instance ) ){
			error_log('payment_webhook_process_callback: '.json_encode( $this->instance ));
			return $this->instance;
		}

		$_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] = "f88eb9f5075834cda2dec52577e7a2c46af04802e20716ffcc7a5c94d4c956da";
		$post = file_get_contents('php://input');

		if (empty($post)) {
			return array('access_denied'=>'Unauthorised access!!!');
		}

		$data = json_decode( $post, true );

		if ( empty( $data['event'] ) === false ) {
			if ( isset( $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ) === true ) {
				$razorpayWebhookSecret_option = get_option('wp_rzp_options');

				if( ! empty( $razorpayWebhookSecret_option ) && isset( $razorpayWebhookSecret_option['wp_rzp_field_webhook_secret_key_id'] ) ) {
					$razorpayWebhookSecret = $razorpayWebhookSecret_option['wp_rzp_field_webhook_secret_key_id'];
				}else {
					$razorpayWebhookSecret = '';
				}

				try {
					$this->instance->utility->verifyWebhookSignature(
						$post,
						$_SERVER['HTTP_X_RAZORPAY_SIGNATURE'],
						$razorpayWebhookSecret
					);
				} catch ( Errors\SignatureVerificationError $e ) {
					$log = array(
						'message' => $e->getMessage(),
						'data'    => $data,
						'event'   => 'razorpay.wp.signature.verify_failed',
					);
					error_log( json_encode( $log ) );

					return $log;
				}

				switch ( $data['event'] ) {
					case self::PAYMENT_CAPATURED:
						return $this->payments->payment_captured( $this->orders, $data );

					default:
						$log = array(
							'message' => 'Event Not Found',
							'data'    => $data,
							'event'   => 'razorpay.wp.event.notfound',
						);
						error_log( json_encode( $log ) );
						return $log;
				}
			} else {
				$log = array(
					'message' => 'HTTP SIGNATURE NOT FOUND',
					'data'    => $data,
					'event'   => 'razorpay.wp.event.signature',
				);
				error_log( json_encode( $log ) );
				return $log;
			}
		} else {
			return array('empty_event_object'=>'Empty event object!!!');
		}
	}

	/**
	 * Insert payments callback.
	 *
	 * @throws Error Thrown when error occured.
	 * @since   0.1.0
	 */
	public function insert_payments_callback( WP_REST_Request $request ) {

		if( is_wp_error( $this->instance ) ){
			error_log('insert_payments_callback: '.json_encode( $this->instance ));
			return $this->instance;
		}

		$args = [
			'count' => 10,
			'skip' => 0
		];

		$url_params = $request->get_query_params();

		if ( isset( $url_params['count'] ) && ! empty( $url_params['count'] ) ) {
			$args['count'] = $url_params['count'];
		}

		if ( isset( $url_params['skip'] ) && ! empty( $url_params['skip'] ) ) {
			$args['skip'] = $url_params['skip'];
		}

		$payments_res = $this->payments->get_payments( $args );

		if( is_wp_error( $payments_res ) ){
			error_log(json_encode( $payments_res ));

			return $payments_res;
		} else {
			error_log( json_encode( $payments_res->toArray() ) );
			do_action( 'after_get_all_payments_callback', $payments_res->toArray(), $args );

			return $payments_res->toArray();
		}
	}
}

