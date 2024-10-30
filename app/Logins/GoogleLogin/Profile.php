<?php
/**
 * @author  Pluginly
 * @since   1.1.0
 * @version 1.7.0
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Utils\User;

class Profile {

	public function __construct() {
		add_filter( 'get_avatar_url', [$this, 'avatar'], 10, 3 );
		add_action( "login_me_now_after_login", [$this, 'verified'], 10, 2 ); // $user_id, $channel_name, $userDataDTO, $this );
	}

	public function verified( $user_id, $channel_name ) {
		if ( 'google' !== $channel_name ) {
			return;
		}

		add_user_meta( $user_id, 'login_me_now_google_verified', true );
	}

	public function avatar( $url, $id_or_email = '' ) {
		if ( empty( $id_or_email ) ) {
			return $url;
		}

		$user_avatar = SettingsRepository::get( 'google_pro_user_avatar', false );
		if ( ! $user_avatar ) {
			return $url;
		}

		if ( null === $id_or_email || is_object( $id_or_email ) ) {
			return $url;
		}

		$user_id = 0;
		if ( is_email( $id_or_email ) && email_exists( $id_or_email ) ) {
			$user    = get_user_by( 'email', $id_or_email );
			$user_id = (int) $user->ID;
		} elseif ( is_int( $id_or_email ) ) {
			$user_id = (int) $id_or_email;
		} else {
			return $url;
		}

		if ( $user_id ) {
			$_url = User::avatar_url( $user_id, 'google' );
			$url  = $_url ? str_replace( '=s96-c', '', $_url ) : $url;
		}

		return $url;
	}
}