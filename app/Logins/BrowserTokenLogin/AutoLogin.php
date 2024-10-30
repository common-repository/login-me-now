<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.2
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;
use LoginMeNow\DTO\LoginDTO;
use LoginMeNow\Repositories\AccountRepository;
use LoginMeNow\Utils\Helper;

class AutoLogin {
	use Singleton;
	use Hookable;

	public function __construct() {
		$this->action( 'init', [$this, 'listen'] );
	}

	public function listen(): void {
		if ( ! isset( $_GET['lmn'] ) ) {
			return;
		}

		if ( empty( $_GET['lmn'] ) ) {
			$title   = __( 'Number Not Provided', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( '/templates/admin/messages/error', ['title' => $title, 'message' => $message] );
			exit();
		}

		/** First thing, check the secret number if not exist return an error*/
		$number  = sanitize_text_field( $_GET['lmn'] );
		$t_value = get_transient( $number );
		if ( ! $t_value ) {
			$title   = __( 'Invalid number', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( '/templates/admin/messages/error', ['title' => $title, 'message' => $message] );
			exit();
		}

		$user_id = ! empty( $t_value ) ? $t_value : false;
		if ( ! $user_id ) {
			$title   = __( 'User not found', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( '/templates/admin/messages/error', ['title' => $title, 'message' => $message] );
			exit();
		}

		delete_transient( $number );

		$redirect_uri = apply_filters( 'login_me_now_browser_token_login_redirect_uri', admin_url() );

		$dto = ( new LoginDTO )
			->set_user_id( $user_id )
			->set_redirect_uri( $redirect_uri )
			->set_redirect_return( false )
			->set_channel_name( 'browser_token' );

		( new AccountRepository )->login( $dto );
	}
}