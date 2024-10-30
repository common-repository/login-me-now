<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Utils;

class Config {

	private static array $file_data = [];

	public static function get( string $key ): string {
		if ( ! self::$file_data ) {
			self::file();
		}

		return self::$file_data[$key] ?? '';
	}

	private static function file(): void {
		$path = __DIR__ . '/../../';
		$prod = $path . '/config.php';
		$dev  = $path . "/config.dev.php";

		if ( ! \file_exists( $prod ) ) {
			return;
		}

		self::$file_data = ( require_once $prod );
		if ( \file_exists( $dev ) ) {
			self::$file_data = ( require_once $dev );
		}
	}
}