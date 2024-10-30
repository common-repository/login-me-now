<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Common;

use LoginMeNow\Common\Singleton;

abstract class ModelBase {
	use Singleton;

	abstract public function setup(): void;
}
