<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */
namespace LoginMeNow\Setup;

use LoginMeNow\Utils\Config;
use LoginMeNow\Models\BrowserTokenModel;
use LoginMeNow\Utils\Random;

class Activate {
	public string $file_name;

	public function __construct( $file_name ) {
		$this->file_name = $file_name;
		$this->auto_deactivate();
		$this->setup();
	}

	public function auto_deactivate(): void {
		if ( Compatibility::php() ) {
			return;
		}

		deactivate_plugins( basename( $this->file_name ) );

		$error = __( '<h1>An Error Occurred</h1>', 'login-me-now' );
		$error .= __( '<h2>Your installed PHP Version is: ', 'login-me-now' ) . PHP_VERSION . '</h2>';
		$error .= __( '<p>The <strong>Login Me Now</strong> plugin requires PHP version <strong>', 'login-me-now' ) . Config::get( 'min_php' ) . __( '</strong> or greater', 'login-me-now' );
		$error .= __( '<p>The version of your PHP is ', 'login-me-now' ) . '<a href="http://php.net/supported-versions.php" target="_blank"><strong>' . __( 'unsupported and old', 'login-me-now' ) . '</strong></a>.';
		$error .= __( 'You should update your PHP software or contact your host regarding this matter.</p>', 'login-me-now' );
		wp_die(
			wp_kses_post( $error ),
			esc_html__( 'Plugin Activation Error', 'login-me-now' ),
			[
				'response'  => 200,
				'back_link' => true,
			]
		);
	}

	public function setup(): void {
		BrowserTokenModel::init()->create_table();

		/**
		 * Add the secret key if not exist
		 */
		$key = get_option( 'login_me_now_secret_key' );
		if ( ! $key ) {
			$key = Random::key();
			update_option( 'login_me_now_secret_key', $key );
		}

		/**
		 * Add the algorithm if not exist
		 */
		$algo = get_option( 'login_me_now_algorithm' );
		if ( ! $algo ) {
			$algo = 'HS256';
			update_option( 'login_me_now_algorithm', $algo );
		}
	}
}
