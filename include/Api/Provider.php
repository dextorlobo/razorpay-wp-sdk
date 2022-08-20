<?php
/**
 * Credentials Provider 
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Imarun\RazorpayWpSdk\Api;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\Error;
use Imarun\RazorpayWpSdk\Exception\InvalidArgumentException;

/**
 * Class Provider
 *
 * @package Imarun\RazorpayWpSdk\Api
 * @since 0.1.0
 */
class Provider {

	/**
	 * Account key.
	 *
	 * @var string
	 */
	private $key;

	/**
	 * Account secret.
	 *
	 * @var string
	 */
	private $secret;

	/**
	 * Provider constructor.
	 *
	 * @since   0.1.0
	 *
	 * @param string $key key.
	 * @param string $secret secret.
	 */
	public function __construct( string $key = '', string $secret = '' ) {
		$this->key    = $key;
		$this->secret = $secret;
	}

	/**
	 * Get key.
	 *
	 * @return bool
	 * @since   0.1.0
	 */
	final public function get_key(): string {
		return $this->key;
	}

	/**
	 * Get secret.
	 *
	 * @return bool
	 * @since   0.1.0
	 */
	final public function get_secret(): string {
		return $this->secret;
	}

	/**
	 * Check if configuration is set.
	 *
	 * @return bool
	 * @since   0.1.0
	 */
	final public function is_configuration_set(): bool {
		return ! empty( $this->key ) && ! empty( $this->secret );
	}

	/**
	 * Create AWS instance.
	 *
	 * @return Api instance
	 * @throws InvalidArgumentException Thrown when error occured.
	 * @since   0.1.0
	 */
	final public function get_instance(): Api {
		if ( ! $this->is_configuration_set() ) {
			throw new InvalidArgumentException( 'Please provide valid credentials!' );
		}

		return new Api( $this->key, $this->secret );
	}
}
