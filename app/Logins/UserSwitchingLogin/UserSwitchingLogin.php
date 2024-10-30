<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\UserSwitchingLogin;

use LoginMeNow\Common\LoginBase;
use LoginMeNow\Logins\UserSwitchingLogin\AdminNotice;
use LoginMeNow\Utils\Module;

class UserSwitchingLogin extends LoginBase {

	public function setup(): void {
		if ( ! Module::is_active( 'user_switching', true ) ) {
			return;
		}

		add_action( 'plugins_loaded', function () {
			require_once ( ABSPATH . '/wp-admin/includes/plugin.php' );

			if ( is_plugin_active( 'user-switching/user-switching.php' ) ) {
				AdminNotice::init();
			} else {
				if ( ! class_exists( 'user_switching' ) ) {
					include_once 'user-switching.php';
				}
			}
		}, 99 );
	}

	public static function show(): bool {
		return true;
	}
}