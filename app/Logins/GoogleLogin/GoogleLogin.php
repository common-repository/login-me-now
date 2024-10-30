<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Common\ModuleBase;
use LoginMeNow\Logins\GoogleLogin\Enqueuer;
use LoginMeNow\Repositories\SettingsRepository;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GoogleLogin extends ModuleBase {

	public function setup(): void {
		( new Settings() );

		if ( ! self::show() ) {
			return;
		}

		( new Profile() );

		Enqueuer::init();
		Route::init();
		Controller::init();
		Button::init();

		if ( ! defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
			OneTap::init();
		}
	}

	public static function show(): bool {
		$enable        = SettingsRepository::get( 'google_login', false );
		$client_id     = SettingsRepository::get( 'google_client_id', '' );
		$client_secret = SettingsRepository::get( 'google_client_secret', '' );

		if ( $enable && $client_id && $client_secret ) {
			return true;
		}

		return false;
	}

	public static function show_on_native_login() {
		return self::show() && SettingsRepository::get( 'google_native_login', true );
	}

	public static function create_auth_url() {
		$client_id    = SettingsRepository::get( 'google_client_id' );
		$redirect_uri = home_url( 'wp-login.php?lmn-google' );
		$auth         = 'https://accounts.google.com/o/oauth2/v2/auth';
		$scopes       = [
			'email',
			'profile',
		];

		$args = [
			'response_type' => 'code',
			'client_id'     => urlencode( $client_id ),
			'redirect_uri'  => urlencode( $redirect_uri ),
			'wpnonce'       => wp_create_nonce( 'lmn-google-nonce' ),
		];

		if ( count( $scopes ) ) {
			$args['scope'] = urlencode( implode( ' ', array_unique( $scopes ) ) );
		}

		return add_query_arg( $args, $auth );
	}
}