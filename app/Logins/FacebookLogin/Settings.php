<?php
/**
 * @author  Pluginly
 * @since   1.7.0
 * @version 1.7.0
 */

namespace LoginMeNow\Logins\FacebookLogin;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_admin_settings_datatypes', [$this, 'register_types'] );
		add_filter( 'login_me_now_dashboard_rest_options', [$this, 'register_options'] );
	}

	public function register_types( array $options ) {
		$options['enable_sign_in_facebook']            = 'bool';

		$options['facebook_login']                     = 'bool';
		$options['facebook_app_id']                    = 'string';
		$options['facebook_app_secret']                = 'string';
		$options['facebook_native_login']              = 'bool';
		$options['facebook_update_existing_user_data'] = 'bool';
		$options['facebook_pro_user_avatar']           = 'bool';

		return $options;
	}

	public function register_options( array $options ) {
		$options['enable_sign_in_facebook']            = false;
		
		$options['facebook_login']                     = false;
		$options['facebook_app_id']                    = '';
		$options['facebook_app_secret']                = '';
		$options['facebook_native_login']              = true;
		$options['facebook_update_existing_user_data'] = false;
		$options['facebook_pro_user_avatar']           = false;

		return $options;
	}
}