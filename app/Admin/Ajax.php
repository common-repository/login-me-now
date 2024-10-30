<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Admin;

use LoginMeNow\Common\Hookable;

class Ajax {
	use Hookable;
	public function __construct() {
		$this->action( 'wp_ajax_login_me_now_update_admin_setting', [Controller::class, 'settings_update'] );
	}
}