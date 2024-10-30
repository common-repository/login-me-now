<?php
/**
 * @author  Pluginly
 * @since   1.0.0
 * @version 1.0.0
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

use LoginMeNow\Common\Singleton;
use LoginMeNow\Utils\Random;
use LoginMeNow\Utils\Time;
use WP_Error;
use WP_User;

/**
 * The Onetime Number Handling Class
 */
class OnetimeNumber {
	use Singleton;

	public function get_shareable_link( int $user_id, int $expiration = 8 ) {
		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		$number = $this->get_new( $user, $expiration );
		if ( ! $number ) {
			return false;
		}

		$link = sprintf( '%s%s', admin_url( '/?lmn=' ), $number );

		return (array) ['link' => $link, 'number' => $number];
	}

	private function get_new( WP_User $user, int $hours ) {
		/** Valid credentials, the user exists create the according Token */
		$issuedAt = Time::now();
		$expire   = apply_filters( 'login_me_now_onetime_number_expire', $issuedAt + ( HOUR_IN_SECONDS * $hours ), $issuedAt );

		$number = Random::number();

		/** Store the generated token in transient*/
		$saved = set_transient( $number, $user->data->ID, $expire );
		if ( ! $saved ) {
			return new WP_Error(
				__( "Something wen't wrong, please try again.", 'login-me-now' ),
			);
		}

		return (int) $number;
	}

	/**
	 * Verify the number
	 */
	public function verify( int $number ) {
		$len = strlen( $number );

		/**
		 * if the number is not valid return an error.
		 */
		if ( ! $number || 16 !== $len ) {
			return new WP_Error(
				'Invalid Number'
			);
		}

		/** Get the user_id from transient */
		$user_id = (int) get_transient( $number );

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		return (int) $user->data->ID;
	}
}