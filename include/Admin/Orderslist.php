<?php
/**
 * Orderslist class.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\RazorpayWpSdk\Admin;
use Imarun\RazorpayWpSdk\Orders;

class Orderslist {

	/**
	 * Orderslist constructor.
	 *
	 * @since   0.1.0
	 */
	public function __construct() {
		add_filter( 'manage_'.Orders::ORDER_POST_TYPE.'_posts_columns', array( $this, 'wprzp_filter_posts_columns' ) );
		add_action( 'manage_'.Orders::ORDER_POST_TYPE.'_posts_custom_column', array( $this, 'wprzp_realestate_column' ), 10, 2);
	}

	public function wprzp_filter_posts_columns( $columns ) {
		$columns = array(
			'cb'    => $columns['cb'],
			'title' => __( 'Payment ID' ),
			'name'  => __( 'Name' ),
			'email' => __( 'Email', 'wprzp' ),
			'date'  => __( 'Date', 'wprzp' ),
		  );

		return $columns;
	}

	public function wprzp_realestate_column( $column, $post_id ) {
		// Name column
		if ( 'name' === $column ) {
			$fname = get_post_meta( $post_id, 'rzp_fname', true );
			$lname = get_post_meta( $post_id, 'rzp_lname', true );
		
			if ( ! $fname && ! $lname ) {
				_e( 'n/a' );  
			} else {
				echo $fname.' '.$lname;
			}
		}

		// Email column
		if ( 'email' === $column ) {
			$email = get_post_meta( $post_id, 'rzp_email', true );

			if ( ! $email ) {
				_e( 'n/a' );  
			} else {
				echo $email;
			}
		}
	}
}
