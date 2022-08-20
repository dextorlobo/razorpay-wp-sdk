<?php
/**
 * Domain level invalid argument exception.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types=1 );

namespace Imarun\RazorpayWpSdk\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;

/**
 * Domain InvalidArgumentException
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */
class InvalidArgumentException extends BaseInvalidArgumentException {
	/**
	 * Gets the status code for the exception.
	 *
	 * @return int
	 */
	public function get_code(): int {
		return 400;
	}
}
