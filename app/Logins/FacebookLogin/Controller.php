<?php
/**
 * @author  Pluginly
 * @since  	1.4.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\FacebookLogin;

use LoginMeNow\DTO\UserDataDTO;
use LoginMeNow\Repositories\SettingsRepository;

class Controller {
	public function listen() {
		if ( ! array_key_exists( 'lmn-facebook', $_GET ) ) {
			return;
		}

		$code = $_GET['code'];
		if ( ! $code ) {
			wp_send_json_error( __( "Not authenticated", 'login-me-now' ) );
		}

		$this->remote( $code );
	}

	private function remote( $code ) {
		$access_token = $this->generate_access_token( $code );
		if ( ! $access_token ) {
			wp_send_json_error( __( "Token not matching", 'login-me-now' ) );
		}

		$user_data = $this->get_remote_user_graph( $access_token );
		if ( ! $user_data ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		} elseif ( ! isset( $user_data['email'] ) ) {
			wp_send_json_error( __( "Please give the email permission", 'login-me-now' ) );
		}

		$userDataDTO = ( new UserDataDTO )
			->set_user_email( $user_data['email'] )
			->set_display_name( $user_data['name'] ?? '' )
			->set_first_name( $user_data['first_name'] ?? '' )
			->set_last_name( $user_data['last_name'] ?? '' )
			->set_user_avatar_url( $user_data['picture']['data']['url'] ?? '' );

		( new Repository )->auth( $userDataDTO );
	}

	private function generate_access_token( string $code ) {
		$args = [
			'timeout'    => 15,
			'user-agent' => 'WordPress',
			'body'       => [
				'client_id'     => SettingsRepository::get( 'facebook_app_id' ),
				'client_secret' => SettingsRepository::get( 'facebook_app_secret' ),
				'redirect_uri'  => home_url( 'wp-login.php?lmn-facebook' ),
				'code'          => $code,
			],
		];

		$request = wp_remote_get(
			'https://graph.facebook.com/v20.0/oauth/access_token',
			$args
		);

		if ( wp_remote_retrieve_response_code( $request ) !== 200 ) {
			wp_send_json_error( __( "Unexpected response", 'login-me-now' ) );
		} else {
			$body = json_decode( wp_remote_retrieve_body( $request ), true );
		}

		return $body['access_token'] ?? '';
	}

	private function get_remote_user_graph( string $access_token ): array {
		$fbApiUrl = 'https://graph.facebook.com/v20.0/me?fields=id,name,email,first_name,last_name,picture.type(large)&access_token=' . $access_token;

		$response            = file_get_contents( $fbApiUrl );
		$data                = json_decode( $response, true );
		$data['accessToken'] = $access_token;

		if ( ! isset( $data['id'] ) ) {
			$data['message'] = 'Something went wrong';
		}

		return $data;
	}
}