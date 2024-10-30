<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.7.0
 */

namespace LoginMeNow\Logins\LinkLogin;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;
use LoginMeNow\DTO\LoginDTO;
use LoginMeNow\Models\UserToken;
use LoginMeNow\Repositories\AccountRepository;
use LoginMeNow\Utils\Helper;

class Authenticate {
	use Singleton;
	use Hookable;

	public function __construct() {
		$this->action( 'init', [$this, 'listen'] );
	}

	public function listen(): void {
		if ( ! isset( $_GET['lmn-token'] ) ) {
			return;
		}

		if ( empty( $_GET['lmn-token'] ) ) {
			$title   = __( 'No Login Link Found', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( '/templates/admin/messages/error', ['title' => $title, 'message' => $message] );
			exit();
		}

		$token   = sanitize_text_field( $_GET['lmn-token'] );
		$user_id = UserToken::init()->verify( $token );

		if ( ! $user_id ) {
			$title   = __( 'Invalid Login Link', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			Helper::get_template_part( '/templates/admin/messages/error', ['title' => $title, 'message' => $message] );
			exit();
		}

		$redirect_uri = apply_filters( 'login_me_now_temporary_login_redirect_uri', admin_url() );
		$message      = __( "logged in using temporary login link", 'login-me-now' );
		\LoginMeNow\Integrations\SimpleHistory\Logs::add( $user_id, $message );

		$dto = ( new LoginDTO )
			->set_user_id( $user_id )
			->set_redirect_uri( $redirect_uri )
			->set_redirect_return( false )
			->set_channel_name( 'link_login' );

		( new AccountRepository )->login( $dto );
	}
}