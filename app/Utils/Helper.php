<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Utils;

class Helper {

	public static function get_file_uri( $path ) {
		$file = LOGIN_ME_NOW_URL . $path;

		return $file;
	}

	public static function get_file_dir() {
		$file = LOGIN_ME_NOW_PATH;

		return $file;
	}

	public static function get_template_part( string $template, $args = [] ) {
		if ( is_array( $args ) ) {
			extract( $args );
		}

		$template = $template . '.php';

		$file = self::get_file_dir() . $template;

		require $file;
	}

	public static function get_users_tokens() {
		global $wpdb;
		$tokens = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key='login_me_now_track_token'", ARRAY_A );

		return $tokens;
	}

	public static function generate_status_options( string $active, int $id ): string {
		$status = [
			'active'  => __( 'Active', 'login-me-now' ),
			'blocked' => __( 'Block', 'login-me-now' ),
			'pause'   => __( 'Pause', 'login-me-now' ),
		];

		if ( 'expired' === $active ) {
			return __( '<span style="color:red;">Expired</span>', 'login-me-now' );
		}

		$html = '<select onchange="updateStatus(event)" data-id="' . $id . '">';
		foreach ( $status as $key => $value ) {
			$html .= sprintf( '<option %s value="%s">%s</option>', ( $key === $active ? 'selected' : '' ), $key, $value );
		}
		$html .= "</select>";

		return $html;
	}

	/**
	 * Get plugin status
	 */
	public static function get_plugin_status( string $plugin_init_file ): string {
		$installed_plugins = get_plugins();

		if ( ! isset( $installed_plugins[$plugin_init_file] ) ) {
			return 'install';
		} elseif ( is_plugin_active( $plugin_init_file ) ) {
			return 'activated';
		} else {
			return 'installed';
		}
	}

	public static function get_user_roles() {
		$roles = wp_roles()->get_names();

		return $roles;
	}

	public static function get_pages(): array {
		$return = [];
		$pages  = get_pages();

		foreach ( $pages as $key => $page ) {
			array_push(
				$return,
				[
					'id'   => $page->ID,
					'name' => $page->post_title,
				]
			);
		}

		return $return;
	}

	/**
	 * Get current user IP Address.
	 */
	public static function get_ip_address() {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		return '0.0.0.0';
	}

	public static function lmn_get_pro_url() {
		return '#';
	}

}