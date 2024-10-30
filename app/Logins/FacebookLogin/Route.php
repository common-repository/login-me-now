<?php
/**
 * @author  Pluginly
 * @since  	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\FacebookLogin;

use LoginMeNow\Common\Hookable;

class Route {
	use Hookable;

	public function __construct() {
		$this->action( 'init', [Controller::class, 'listen'] );
	}
}