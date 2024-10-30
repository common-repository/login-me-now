<?php
/**
 * @author  Pluginly
 * @since   1.7.0
 * @version 1.7.0
 */

namespace LoginMeNow\Logins\FacebookLogin;

class Profile {

	public function __construct() {
		add_action( "login_me_now_after_login", [$this, 'verified'], 10, 2 ); // $user_id, $channel_name, $userDataDTO, $this );
	}

	public function verified( $user_id, $channel_name ) {
		if ( 'facebook' !== $channel_name ) {
			return;
		}

		add_user_meta( $user_id, 'login_me_now_facebook_verified', true );
	}
}