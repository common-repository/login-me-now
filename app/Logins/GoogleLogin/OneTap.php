<?php
/**
 * @author  Pluginly
 * @since   1.0.0
 * @version 1.5.0
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;

class OneTap {
	use Singleton;
	use Hookable;

	public function __construct() {
		$this->filter( 'login_me_now_google_login_show_onetap', [$this, 'onetap'] );
	}

	public function onetap() {
		if ( ! $this->show() ) {
			return false;
		}

		if ( is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	private function show(): bool {
		$show_on = SettingsRepository::get( 'google_onetap_display_location', 'side_wide' );

		$return = false;

		switch ( $show_on ) {
			case 'side_wide':
			case 'selected_pages':
				$return = true;
				break;

			case 'login_screen':
				$return = is_login();
				break;
		}

		return $return;
	}
}