<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Utils;

class Options {
	public static function get( string $key, $default = false ) {
		return get_option( Key::get( $key ), $default );
	}

	public static function set( string $key, $value, $autoload = 'no' ): bool {
		return update_option( Key::get( $key ), $value, $autoload );
	}

	public static function delete( string $key ): bool {
		return delete_option( Key::get( $key ) );
	}
}