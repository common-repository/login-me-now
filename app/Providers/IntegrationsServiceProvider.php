<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Providers;

use LoginMeNow\Common\IntegrationBase;
use LoginMeNow\Common\ProviderBase;

class IntegrationsServiceProvider extends ProviderBase {

	public function boot() {
		foreach ( $this->get() as $_i ) {
			$i = new $_i();
			if ( $i instanceof IntegrationBase ) {
				$i->boot();
			}
		}
	}

	public function get(): array {
		return [
			\LoginMeNow\Integrations\Directorist\Directorist::class,
			\LoginMeNow\Integrations\WooCommerce\WooCommerce::class,
			\LoginMeNow\Integrations\SimpleHistory\SimpleHistory::class,
		];
	}
}