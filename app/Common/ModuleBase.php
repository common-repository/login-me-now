<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Common;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;

abstract class ModuleBase {
	use Singleton;
	use Hookable;

	public function __construct() {
		$this->setup();
	}

	abstract public function setup(): void;
	abstract public static function show(): bool;
}
