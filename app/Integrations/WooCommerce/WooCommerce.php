<?php
/**
 * @author 	Pluginly
 * @since	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Integrations\WooCommerce;

use LoginMeNow\Common\IntegrationBase;
use LoginMeNow\Providers\LoginFormsServiceProvider;

class WooCommerce extends IntegrationBase {
	public function boot(): void {
		add_action( 'woocommerce_login_form_start', [$this, 'add_form'] );
	}

	public function add_form() {
		( new LoginFormsServiceProvider() )->login_buttons( );
	}
}