<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Utils;

class Time {

	public static function expired( int $expire_on ): bool {
		return self::now() > $expire_on;
	}

	public static function now(): int {
		return time();
	}

	public static function zone(): string {
		return wp_timezone_string();
	}

	public static function convert_timestamp( string $date_time_string ): int {
		$dateString = preg_replace( '/\s+\(.+\)/', '', $date_time_string );
		$timestamp  = strtotime( $dateString );

		return $timestamp;
	}
}