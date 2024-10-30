<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Utils;

class Post {
	public static function exists( int $id ): bool {
		return self::status( $id ) ? true : false;
	}

	public static function status( int $id ): string {
		return get_post_status( $id ) ?? '';
	}

	public static function type( int $id ): string {
		return get_post_type( $id ) ?? '';
	}
}