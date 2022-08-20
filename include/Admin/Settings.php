<?php
/**
 * Settings class.
 *
 * @package razorpay-wp-sdk
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\RazorpayWpSdk\Admin;

class Settings {

	/**
	 * Settings constructor.
	 *
	 * @since   0.1.0
	 */
	public function __construct() {
		/**
		 * Register our wp_rzp_settings_init to the admin_init action hook.
		 */
		add_action( 'admin_init', array( $this, 'wp_rzp_settings_init' ) );

		/**
		 * Register our wp_rzp_options_page to the admin_menu action hook.
		 */
		add_action( 'admin_menu', array( $this, 'wp_rzp_options_page' ) );
	}
	
	/**
	 * custom option and settings
	 */
	public function wp_rzp_settings_init() {
		// Register a new setting for "wp_rzp" page.
		register_setting( 'wp_rzp', 'wp_rzp_options' );
	
		// Register a new section in the "wp_rzp" page.
		add_settings_section(
			'wp_rzp_section_developers',
			__( 'Razorpay API settings.', 'wp_rzp' ), array( $this, 'wp_rzp_section_developers_callback' ),
			'wp_rzp'
		);
	
		// Register a new field in the "wp_rzp_section_developers" section, inside the "wp_rzp" page.
		add_settings_field(
			'wp_rzp_field_api', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Key ID', 'wp_rzp' ),
			array( $this, 'wp_rzp_field_key_id_cb' ),
			'wp_rzp',
			'wp_rzp_section_developers',
			array(
				'label_for'          => 'wp_rzp_field_key_id',
				'class'              => 'wp_rzp_row',
			)
		);

		// Register a new field in the "wp_rzp_section_developers" section, inside the "wp_rzp" page.
		add_settings_field(
			'wp_rzp_field_secret_key_id', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Secret key ID', 'wp_rzp' ),
			array( $this, 'wp_rzp_field_secret_key_id_cb' ),
			'wp_rzp',
			'wp_rzp_section_developers',
			array(
				'label_for'          => 'wp_rzp_field_secret_key_id',
				'class'              => 'wp_rzp_row',
			)
		);

		// Register a new field in the "wp_rzp_section_developers" section, inside the "wp_rzp" page.
		add_settings_field(
			'wp_rzp_field_webhook_secret_key_id', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Webhook secret key ID', 'wp_rzp' ),
			array( $this, 'wp_rzp_field_webhook_secret_key_id_cb' ),
			'wp_rzp',
			'wp_rzp_section_developers',
			array(
				'label_for'          => 'wp_rzp_field_webhook_secret_key_id',
				'class'              => 'wp_rzp_row',
			)
		);
	}

	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	public function wp_rzp_section_developers_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Enter the Razorpay keys.', 'wp_rzp' ); ?></p>
		<?php
	}

	/**
	 * Key id field callbakc function.
	 *
	 * @param array $args
	 */
	public function wp_rzp_field_key_id_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_rzp_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_rzp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ]; ?>" >
		<?php
	}

	/**
	 * Secre key id field callbakc function.
	 *
	 * @param array $args
	 */
	public function wp_rzp_field_secret_key_id_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_rzp_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='password' id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_rzp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<?php
	}

	/**
	 * Secre key id field callbakc function.
	 *
	 * @param array $args
	 */
	public function wp_rzp_field_webhook_secret_key_id_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_rzp_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='password' id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_rzp_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<?php
	}

	/**
	 * Add the top level menu page.
	 */
	public function wp_rzp_options_page() {
		add_menu_page(
			'WP Razorpay Settings',
			'Razorpay Options',
			'manage_options',
			'wp_rzp',
			array( $this, 'wp_rzp_options_page_html' )
		);
	}

	/**
	 * Top level menu callback function
	 */
	public function wp_rzp_options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	
		// add error/update messages
	
		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'wp_rzp_messages', 'wp_rzp_message', __( 'Settings Saved', 'wp_rzp' ), 'updated' );
		}
	
		// show error/update messages
		settings_errors( 'wp_rzp_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "wp_rzp"
				settings_fields( 'wp_rzp' );
				// output setting sections and their fields
				// (sections are registered for "wp_rzp", each field is registered to a specific section)
				do_settings_sections( 'wp_rzp' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}
}
