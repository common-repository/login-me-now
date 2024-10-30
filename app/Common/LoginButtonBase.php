<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Common;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;

abstract class LoginButtonBase {
	use Singleton;
	use Hookable;

	abstract public function button(): string;
	abstract public function html( int $width = 300 ): string;
	abstract public function shortcodes(): void;
	abstract public function native_login(): bool;

	public function __construct() {
		$this->action( 'init', [$this, 'shortcodes'] );
	}
}
