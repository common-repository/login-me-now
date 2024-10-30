<?php
/**
 * @author  Pluginly
 * @since   1.5.0
 * @version 1.6.2
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Common\LoginButtonBase;
use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Utils\User;

class Button extends LoginButtonBase {

	public function shortcodes(): void {
		add_shortcode( 'login_me_now_google_button', [$this, 'button'] );
	}

	public function button(): string {
		if ( ! GoogleLogin::show() ) {
			return '';
		}

		if ( User::is_logged_in() ) {
			return '';
		}

		wp_enqueue_style( 'login-me-now-social-login-main' );
		wp_enqueue_script( 'login-me-now-social-login-main' );

		return $this->html();
	}

	public function html( int $width = 270 ): string {
		$width = apply_filters( 'login_me_now_google_button_width', $width );

		ob_start();
		include __DIR__ . '/Views/Button.php';
		$html = ob_get_clean();

		return $html;
	}

	public function native_login(): bool {
		return SettingsRepository::get( 'google_native_login', true );
	}
}