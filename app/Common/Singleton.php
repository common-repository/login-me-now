<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Common;

trait Singleton {

	private static $instances = [];

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something wen\'t wrong.', 'login-me-now' ), ' 1.0.0' );
	}

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something wen\'t wrong.', 'login-me-now' ), '1.0.0' );
	}

	public static function init( ...$args ): object{
		$class = get_called_class();
		if ( ! isset( self::$instances[$class] ) ) {
			self::$instances[$class] = new $class( ...$args );
		}

		return self::$instances[$class];
	}
}
