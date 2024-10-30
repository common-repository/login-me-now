<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Frontend;

use LoginMeNow\Common\EnqueuerBase;
use LoginMeNow\Repositories\SettingsRepository;

class Enqueuer extends EnqueuerBase {

	public function wp_enqueue_scripts(): void {
		$this->register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
		$this->register_script( 'login-me-now-social-login-main', LOGIN_ME_NOW_PUBLIC . 'js/main.js', ['login-me-now-google-api'] );

		$this->register_style( 'login-me-now-social-login-main', LOGIN_ME_NOW_PUBLIC . 'css/main.css' );
		$this->local_script();
	}

	public function login_enqueue_scripts(): void {
		$this->register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
		$this->register_script( 'login-me-now-social-login-main', LOGIN_ME_NOW_PUBLIC . 'js/main.js', ['login-me-now-google-api'] );

		$this->register_style( 'login-me-now-social-login-main', LOGIN_ME_NOW_PUBLIC . 'css/main.css' );
		$this->local_script();
	}

	public function local_script() {
		$redirect = admin_url();

		$data = [
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'facebook_app_id'           => SettingsRepository::get( 'facebook_app_id', '' ),
			'facebook_pro_redirect_url' => $redirect,
			'google_client_id'          => SettingsRepository::get( 'google_client_id', '' ),
			'google_pro_redirect_url'   => $redirect,
		];

		if ( defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
			$data['facebook_pro_redirect_url'] = SettingsRepository::get( 'facebook_pro_redirect_url', $redirect );
			$data['google_pro_redirect_url']   = SettingsRepository::get( 'google_pro_redirect_url', $redirect );
		}

		wp_localize_script( 'login-me-now-social-login-main', 'login_me_now_social_login_main_obj', $data );
	}
}