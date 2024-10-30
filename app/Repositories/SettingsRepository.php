<?php
/**
 * @author  Pluginly
 * @since  	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Repositories;

use LoginMeNow\Utils\Helper;

class SettingsRepository {
	private static string $option_name = 'login_me_now_admin_settings';
	private static array $settings;
	private static $instance;

	public function __construct() {
		self::$settings = get_option( self::$option_name, [] );
	}

	public static function init(): object {
		$class = get_called_class();
		if ( ! isset( self::$instance ) ) {
			self::$instance = new $class();
		}

		return self::$instance;
	}

	public function all(): array {
		$db_option = get_option( self::$option_name, [] );

		$defaults = apply_filters(
			'login_me_now_dashboard_rest_options',
			[
				'logs'            => true,

				'activity_logs'   => false,
				'social_login'    => true,
				'temporary_login' => true,

				'user_switching'  => true,

				'get_user_roles'  => Helper::get_user_roles(),
				'get_pages'       => Helper::get_pages(), // #Improvement Needed
			]
		);

		$updated_option = wp_parse_args( $db_option, $defaults );

		return $updated_option;
	}

	public static function get( string $key, $default = null ) {
		self::$settings = get_option( self::$option_name, [] );

		return self::$settings[$key] ?? $default;
	}

	public static function update( string $key, $value ): void {
		self::$settings       = get_option( self::$option_name, [] );
		self::$settings[$key] = $value;
		update_option( self::$option_name, self::$settings );
	}
}