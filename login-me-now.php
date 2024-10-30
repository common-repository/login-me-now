<?php
/**
 * Plugin Name: Login Me Now
 * Description: 1 click passwordless login, social login & user switching
 * Author: Pluginly
 * Author URI: https://loginmenow.com/
 * Version: 1.6.2
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: login-me-now
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include __DIR__ . '/vendor/autoload.php';

final class LoginMeNow {
	private static LoginMeNow $instance;

	public static function instance(): LoginMeNow {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function load(): void {
		register_activation_hook(
			__FILE__, function () {
				new LoginMeNow\Setup\Activate( __FILE__ );
			}
		);

		register_deactivation_hook(
			__FILE__, function () {
				new LoginMeNow\Setup\Deactivate();
			}
		);

		$application = \LoginMeNow\Boot\App::instance();
		$application->boot( __FILE__, __DIR__ );

		add_action(
			'plugins_loaded', function () use ( $application ): void {

				do_action( 'before_login_me_now_load' );

				$application->load();

				do_action( 'after_login_me_now_load' );
			}
		);
	}
}

LoginMeNow::instance()->load();