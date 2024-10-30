<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\LinkLogin;

use LoginMeNow\Common\LoginBase;
use LoginMeNow\Common\Singleton;
use LoginMeNow\Models\UserToken;
use LoginMeNow\Utils\Module;
use LoginMeNow\Utils\Random;
use LoginMeNow\Utils\Time;
use LoginMeNow\Utils\Translator;
use WP_User;

/**
 * The Login Link Handling Class
 */
class LinkLogin extends LoginBase {
	use Singleton;

	private $token_key = 'lmn_token';
	private $error;

	public function setup(): void {
		if ( ! Module::is_active( 'temporary_login', true ) ) {
			return;
		}

		Ajax::init();
		Authenticate::init();
	}

	public static function show(): bool {
		return true;
	}

	public function create( int $user_id, int $expiration ) {
		if ( ! function_exists( 'get_userdata' ) ) {
			require_once ABSPATH . WPINC . '/pluggable.php';
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		$token = $this->generate_token( $user, $expiration );
		if ( ! $token ) {
			return false;
		}

		$link = sprintf( '%s%s', admin_url( '/?lmn-token=' ), $token );

		return [
			'link'    => $link,
			'message' => __( 'Login link generated successfully!', 'login-me-now' ),
		];
	}

	protected function generate_token( WP_User $user, int $secs ): string {
		$issued_at = Time::now();
		$expire    = apply_filters( 'login_me_now_login_link_expire', $secs, $issued_at );

		$number = Random::number();
		$token  = Translator::encode( $user->data->ID, $number, $expire, '==' );

		UserToken::init()->insert(
			$user->data->ID,
			[
				'number'     => $number,
				'created_at' => $issued_at,
				'created_by' => get_current_user_id(),
				'expire'     => $expire,
			]
		);

		\LoginMeNow\Integrations\SimpleHistory\Logs::add( $user->data->ID, "generated a temporary login link" );

		return $token;
	}

	public function get( int $umeta_id ): string {
		$result = UserToken::init()->get( $umeta_id );

		if ( ! $result ) {
			return __( 'No token found', 'login-me-now' );
		}

		$meta_value = $result[0]->meta_value ?? null;
		if ( ! $meta_value ) {
			return __( 'No token found', 'login-me-now' );
		}

		$meta_value = maybe_unserialize( $meta_value );

		$user_id = $meta_value['created_by'] ?? 0;
		$number  = $meta_value['number'] ?? 0;
		$expire  = $meta_value['expire'] ?? 0;

		$token = Translator::encode( $user_id, $number, $expire, '==' );

		\LoginMeNow\Integrations\SimpleHistory\Logs::add( $user_id, "generated a temporary login link" );

		return sprintf( '%s%s', admin_url( '/?lmn-token=' ), $token );
	}
}