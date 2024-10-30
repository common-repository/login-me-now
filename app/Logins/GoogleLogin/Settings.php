<?php
/**
 * @author  Pluginly
 * @since   1.7.0
 * @version 1.7.0
 */

namespace LoginMeNow\Logins\GoogleLogin;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_admin_settings_datatypes', [$this, 'register_types'] );
		add_filter( 'login_me_now_dashboard_rest_options', [$this, 'register_options'] );
	}

	public function register_types( array $options ) {
		$options['google_login']                   = 'bool';
		$options['google_client_id']               = 'string';
		$options['google_client_secret']           = 'string';
		$options['google_native_login']            = 'bool';
		$options['google_onetap']                  = 'bool';
		$options['google_cancel_on_tap_outside']   = 'bool';
		$options['google_onetap_display_location'] = 'string';

		return $options;
	}

	public function register_options( array $options ) {
		$options['google_login']                     = false;
		$options['google_client_id']                 = '';
		$options['google_client_secret']             = '';
		$options['google_native_login']              = true;
		$options['google_update_existing_user_data'] = false;
		$options['google_pro_user_avatar']           = false;
		$options['google_cancel_on_tap_outside']     = false;
		$options['google_onetap_display_location']   = 'siteWide';

		return $options;
	}
}