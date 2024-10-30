<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_admin_settings_datatypes', [$this, 'register_types'] );
		add_filter( 'login_me_now_dashboard_rest_options', [$this, 'register_options'] );
	}

	public function register_types( array $options ) {
		$options['browser_extension'] = 'bool';

		return $options;
	}

	public function register_options( array $options ) {
		$options['browser_extension'] = true;

		return $options;
	}
}