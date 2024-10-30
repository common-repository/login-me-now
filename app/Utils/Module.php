<?php
/**
 * @author  Pluginly
 * @since   1.2.0
 * @version 1.6.0
 */

namespace LoginMeNow\Utils;

use LoginMeNow\Repositories\SettingsRepository;

class Module {
	public static function is_active( string $key, bool $default = false ): bool {
		return SettingsRepository::get( $key, $default );
	}
}