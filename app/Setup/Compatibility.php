<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */
namespace LoginMeNow\Setup;

use LoginMeNow\Utils\Config;

class Compatibility {
	public static function php(): bool {
		if ( version_compare( PHP_VERSION, Config::get( 'min_php' ), '<' ) ) {
			return false;
		}

		return true;
	}
}
