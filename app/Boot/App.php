<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.2
 */

namespace LoginMeNow\Boot;

use LoginMeNow\Utils\Config;

class App {

	public static bool $loaded;
	public static App $instance;

	public static function instance() {
		if ( empty( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function boot( $plugin_root_file, $plugin_root_path ) {
		if ( ! empty( static::$loaded ) ) {
			return;
		}

		$this->define_constants( $plugin_root_file );
	}

	public function load() {
		if ( ! empty( static::$loaded ) ) {
			return;
		}

		( new \LoginMeNow\Admin\Admin() );
		( new \LoginMeNow\Frontend\Enqueuer() );
		
		( new \LoginMeNow\Providers\IntegrationsServiceProvider() )->boot();
		( new \LoginMeNow\Providers\LoginsServiceProvider() )->boot();
		( new \LoginMeNow\Providers\LoginFormsServiceProvider() )->boot();

		static::$loaded = \true;
	}

	private function define_constants( $plugin_root_file ): void {
		define( 'LOGIN_ME_NOW_VERSION', Config::get( 'version' ) );
		define( 'LOGIN_ME_NOW_FILE', $plugin_root_file );
		define( 'LOGIN_ME_NOW_URL', plugins_url( '', LOGIN_ME_NOW_FILE ) );
		define( 'LOGIN_ME_NOW_PATH', dirname( LOGIN_ME_NOW_FILE ) );
		define( 'LOGIN_ME_NOW_APP_PATH', LOGIN_ME_NOW_PATH . '/app/' );
		define( 'LOGIN_ME_NOW_APP_URL', LOGIN_ME_NOW_URL . '/app/' );

		define( 'LOGIN_ME_NOW_INCLUDES', LOGIN_ME_NOW_APP_PATH . 'Common' );
		define( 'LOGIN_ME_NOW_LOGINS', LOGIN_ME_NOW_APP_PATH . 'Logins' );
		define( 'LOGIN_ME_NOW_ASSETS', LOGIN_ME_NOW_URL . '/assets' );
		define( 'LOGIN_ME_NOW_PUBLIC', LOGIN_ME_NOW_URL . '/public/' );
		define( 'LOGIN_ME_NOW_TEMPLATE_PATH', LOGIN_ME_NOW_PATH . '/templates/' );

		define( 'LOGIN_ME_NOW_ADMIN_URL', LOGIN_ME_NOW_APP_URL . 'Admin' );
		define( 'LOGIN_ME_NOW_ADMIN_PATH', LOGIN_ME_NOW_APP_PATH . 'Admin' );

		define( 'LOGIN_ME_NOW_MENU_SLUG', apply_filters( 'login_me_now_menu_slug', Config::get( 'menu_slug' ) ) );
		define( 'LOGIN_ME_NOW_MENU_CAPABILITY', apply_filters( 'login_me_now_menu_capability', Config::get( 'menu_cap' ) ) );

		define( 'LOGIN_ME_NOW_PRO_UPGRADE_URL', Config::get( 'pro_upgrade_url' ) );
	}
}