<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.7.0
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;
use LoginMeNow\Utils\Module;

class BrowserTokenLogin {
	use Singleton;
	use Hookable;

	public function __construct() {
		( new Settings() );

		if ( ! Module::is_active( 'browser_extension', true ) ) {
			return;
		}

		$this->action( 'admin_footer', [$this, 'lmn_save_popup'] );

		Ajax::init();
		AutoLogin::init();
		( new Route() );
	}

	public function lmn_save_popup(): void {
		include_once __DIR__ . '/Views/extension-popup.php';
	}
}