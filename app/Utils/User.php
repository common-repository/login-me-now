<?php
/**
 * @author  Pluginly
 * @since   1.4.0
 * @version 1.4.0
 */

namespace LoginMeNow\Utils;

use LoginMeNow\Repositories\SettingsRepository;
use WP_User;

class User {
	public static function data( int $user_id ) {
		if ( ! function_exists( 'get_userdata' ) ) {
			require_once ABSPATH . WPINC . '/pluggable.php';
		}

		return get_userdata( $user_id );
	}

	public static function display_name( int $user_id ) {
		$user_data = self::data( $user_id );

		if ( $user_data ) {
			if ( ! empty( $user_data->display_name ) ) {
				return $user_data->display_name;
			} elseif ( ! empty( $user_data->first_name ) ) {
				return $user_data->first_name;
			} else {
				return $user_data->user_login;
			}
		}

		return __( 'Invalid User', 'login-me-now' );
	}

	public static function login( int $user_id ): string {
		$user_data = self::data( $user_id );

		if ( ! $user_data || empty( $user_data->user_login ) ) {
			return __( 'Invalid User', 'login-me-now' );
		}

		return $user_data->user_login;

	}

	public static function avatar_url( int $user_id, string $platform ): string {
		$avatar_url = '';

		switch ( $platform ) {
			case 'google':
				$avatar_url = get_user_meta( $user_id, 'login_me_now_google_profile_picture_url', true );
				break;

			case 'facebook';
				$avatar_url = get_user_meta( $user_id, 'login_me_now_facebook_profile_picture_url', true );
				break;
		}

		return $avatar_url;
	}

	public static function update_profile( int $user_id, array $data, string $channel ): void {
		$user_data                 = [];
		$user_data['ID']           = $user_id;
		$user_data['first_name']   = $data['first_name'] ?? '';
		$user_data['last_name']    = $data['last_name'] ?? '';
		$user_data['display_name'] = $data['display_name'] ?? '';
		$user_data['picture']      = $data['picture'] ?? '';

		wp_update_user( $user_data );
		update_user_meta( $user_id, 'nickname', $user_data['first_name'] );

		if ( isset( $user_data['picture'] ) && ! empty( $user_data['picture'] ) ) {
			update_user_meta(
				$user_id,
				"login_me_now_{$channel}_profile_picture_url",
				esc_url_raw( $user_data['picture']
				)
			);
		}
	}

	public static function set_role( int $user_id, string $channel ): void {
		$default_role = SettingsRepository::get( "{$channel}_pro_default_user_role", false );

		if ( ! is_wp_error( $user_id ) && $default_role ) {
			$user = new WP_User( $user_id );
			$user->remove_role( 'subscriber' );
			$user->add_role( $default_role );
		}
	}

	private static function includes() {
		if ( ! function_exists( 'get_userdata' ) ) {
			require_once ABSPATH . WPINC . '/pluggable.php';
		}
	}

	public static function is_logged_in(): bool {
		self::includes();

		return is_user_logged_in();
	}
}