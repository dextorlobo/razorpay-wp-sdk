<?php
/**
 * Orders Class.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\RazorpayWpSdk;
use Imarun\RazorpayWpSdk\Invoice\Template;

class Orders {

	const ORDER_POST_TYPE = 'rzp_orders';

	private $payment_id;
	private $order_id;
	private $status;
	private $fname;
	private $lname;
	private $email;
	private $contact;
	private $amount;
	private $actual_amount;
	private $cgst_amount;
	private $sgst_amount;
	private $amount_in_words;

	public function get_payment_id() {
		return $this->payment_id;
	}

	public function get_order_id() {
		return $this->order_id;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_order_date() {
		return $this->order_date;
	}

	public function get_fname() {
		return $this->fname;
	}

	public function get_lname() {
		return $this->lname;
	}

	public function get_email() {
		return $this->email;
	}

	public function get_contact() {
		return $this->contact;
	}

	public function get_amount() {
		return $this->amount;
	}

	public function get_actual_amount() {
		$this->actual_amount = $this->get_amount() - ( $this->get_cgst_amount() + $this->get_cgst_amount() );
		return $this->actual_amount;
	}

	public function get_cgst_amount() {
		$this->cgst_amount = bcdiv( strval( ( $this->amount - ( $this->amount / 1.05 ) ) / 2 ), '1', 2 );
		return $this->cgst_amount;
	}

	public function get_sgst_amount() {
		$this->sgst_amount = bcdiv( strval( ( $this->amount - ( $this->amount / 1.05 ) ) / 2 ), '1', 2 );
		return $this->sgst_amount;
	}

	public function get_amount_in_words(){
		$number = floatval( $this->get_amount() );
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen( strval( $no ) );
		$i = 0;
		$str = array();
		$words = array(0 => '', 1 => 'one', 2 => 'two',
			3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
			7 => 'seven', 8 => 'eight', 9 => 'nine',
			10 => 'ten', 11 => 'eleven', 12 => 'twelve',
			13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
			16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
			19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
			40 => 'forty', 50 => 'fifty', 60 => 'sixty',
			70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
		$digits = array('', 'hundred','thousand','lakh', 'crore');
		while( $i < $digits_length ) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
			} else $str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		$this->amount_in_words = ($Rupees ? ucfirst( $Rupees ) . 'Rupees Only' : '') . $paise;

		return $this->amount_in_words;
	}

	/**
	 * Register orders post type.
	 *
	 * @since   0.1.0
	 */
	public function register_orders_post_type() {
		register_post_type(
			self::ORDER_POST_TYPE,
			array(
				'labels'       => array(
					'name'               => __( 'Razorpay Orders', 'razorpay-wp-sdk' ),
					'singular_name'      => __( 'Order', 'razorpay-wp-sdk' ),
					'add_new'            => __( 'Add New', 'razorpay-wp-sdk' ),
					'add_new_item'       => __( 'Add New Order', 'razorpay-wp-sdk' ),
					'edit_item'          => __( 'Edit Order', 'razorpay-wp-sdk' ),
					'new_item'           => __( 'New Order', 'razorpay-wp-sdk' ),
					'all_items'          => __( 'All Orders', 'razorpay-wp-sdk' ),
					'view_item'          => __( 'View Orders', 'razorpay-wp-sdk' ),
					'search_items'       => __( 'Search Orders', 'razorpay-wp-sdk' ),
					'not_found'          => __( 'No order found', 'razorpay-wp-sdk' ),
					'not_found_in_trash' => __( 'No order found in the trash', 'razorpay-wp-sdk' ),
					'parent_item_colon'  => '’',
					'menu_name'          => 'Razorpay Orders',
				),
				'public'       => true,
				'has_archive'  => true,
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions' ),
				'map_meta_cap' => true,
				'menu_icon'    => 'dashicons-media-text',
			)
		);
	}

	/**
	 * Has order already inserted.
	 * 
	 * @since   0.1.0
	 */
	public function has_order_already_inserted() {
		$args = array(
			'post_type'  => self::ORDER_POST_TYPE,
			'meta_query' => array(
				'relation' => 'AND', //**** Use AND or OR as per your required Where Clause
				array(
					'key'     => 'rzp_payment_id',
					'value'   => $this->get_payment_id(),
				),
				array(
					'key'     => 'rzp_status',
					'value'   => $this->get_status(),
				),
			),
		);
		$query = new \WP_Query( $args );

		if( $query->post_count === 0 ){
			return false;
		}

		return true;

	}

	/**
	 * Insert order.
	 * 
	 * @since   0.1.0
	 */
	public function insert_order( $data ) {
		$this->amount     = number_format( $data['payload']['payment']['entity']['amount']/100, 2 );
		$this->status     = $data['payload']['payment']['entity']['status'];
		$this->payment_id = $data['payload']['payment']['entity']['id'];
		$this->order_id   = $data['payload']['payment']['entity']['order_id'];
		$this->email      = $data['payload']['payment']['entity']['email'];
		$this->contact    = $data['payload']['payment']['entity']['contact'];
		$this->order_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $data['created_at'] ), 'Y-m-d H:i:s' );

		if( array_key_exists('first_name', $data['payload']['payment']['entity']['notes'] ) ){
			$this->fname = $data['payload']['payment']['entity']['notes']['first_name'];
		}

		if( array_key_exists('last_name', $data['payload']['payment']['entity']['notes'] ) ){
			$this->lname = $data['payload']['payment']['entity']['notes']['last_name'];
		}

		if( ! $this->has_order_already_inserted() ) {
			return $this->wp_insert_rzp_order();
		} else {
			$log = array(
				'message' => 'Order already exists',
				'data'    => $data,
				'event'   => 'razorpay.wp.webhook.verify_order',
			);
			error_log( json_encode( $log ) );
			return $log;
		}
	}

	/**
	 * Insert all payments in wp.
	 * Hook.
	 * 
	 * @since   0.1.0
	 */
	public function insert_all_payments_in_wp( $data_array, $args ) {

		if ( $data_array['count'] > 0 ) {
			foreach ( $data_array['items'] as $item => $data ) {
				if ( $data['status'] == 'captured' ) {
					$this->amount     = number_format( $data['amount']/100, 2 );
					$this->status     = $data['status'];
					$this->payment_id = $data['id'];
					$this->order_id   = $data['order_id'];
					$this->email      = $data['email'];
					$this->contact    = $data['contact'];
					$this->order_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $data['created_at'] ), 'Y-m-d H:i:s' );

					if( array_key_exists('first_name', $data['notes'] ) ){
						$this->fname = $data['notes']['first_name'];
					}
	
					if( array_key_exists('last_name', $data['notes'] ) ){
						$this->lname = $data['notes']['last_name'];
					}

					if( ! $this->has_order_already_inserted() ) {
						$this->wp_insert_rzp_order();
					} else {
						$log = array(
							'message' => 'Order already exists',
							'data'    => $data,
							'event'   => 'razorpay.wp.insert_payments.verify_order',
						);
						error_log( json_encode( $log ) );
					}
				}
			}
		}
	}

	private function wp_insert_rzp_order() {
		$invoice_template = new Template( $this );
		$invoice_template->set_invoice_template_data();

		$post_id[ $this->get_payment_id() ] = wp_insert_post(
			array(
				'post_status'  => 'publish',
				'post_type'    => self::ORDER_POST_TYPE,
				'post_title'   => $this->get_payment_id(),
				'post_date'    => $this->get_order_date(),
				'post_content' => $invoice_template->get_invoice_html_template(),
				'meta_input'   => array(
					'rzp_payment_id' => $this->get_payment_id(),
					'rzp_order_id'   => $this->get_order_id(),
					'rzp_status'     => $this->get_status(),
					'rzp_amount'     => $this->get_amount(),
					'rzp_email'      => $this->get_email(),
					'rzp_phone'      => $this->get_contact(),
					'rzp_fname'      => $this->get_fname(),
					'rzp_lname'      => $this->get_lname(),
				),
			)
		);

		return $post_id;
	}
}

