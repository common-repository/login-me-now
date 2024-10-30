<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Providers;

use LoginMeNow\Common\LoginBase;
use LoginMeNow\Common\ProviderBase;

class LoginsServiceProvider extends ProviderBase {

	public function boot() {
		foreach ( $this->get() as $_l ) {
			$l = new $_l();
			if ( $l instanceof LoginBase ) {
				$l->setup();
			}
		}
	}

	public function get(): array {
		return [
			\LoginMeNow\Logins\BrowserTokenLogin\BrowserTokenLogin::class,
			\LoginMeNow\Logins\FacebookLogin\FacebookLogin::class,
			\LoginMeNow\Logins\GoogleLogin\GoogleLogin::class,
			\LoginMeNow\Logins\LinkLogin\LinkLogin::class,
			\LoginMeNow\Logins\UserSwitchingLogin\UserSwitchingLogin::class,
		];
	}
}