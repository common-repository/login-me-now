<?php
/**
 * @author  Pluginly
 * @since  	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;

class Route {
	use Hookable;
	use Singleton;

	public function __construct() {
		$this->action( 'init', [Controller::class, 'listen'] );
	}
}