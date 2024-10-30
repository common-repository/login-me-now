<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Utils;

class Random {

	public static function number( int $len = 16 ): int {
		$number = mt_rand( 1000000000000000, 9999999999999999 );

		return $number;
	}

	public static function key( int $len = 40 ): string {
		$characters = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTWYXZ";
		$key        = '';
		for ( $i = 0; $i < $len; $i++ ) {
			$key .= $characters[rand( 0, 34 )];
		}

		return $key;
	}
}