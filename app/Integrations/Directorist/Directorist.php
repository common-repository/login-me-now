<?php
/**
 * @author 	Pluginly
 * @since	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Integrations\Directorist;

use LoginMeNow\Common\IntegrationBase;
use LoginMeNow\Providers\LoginFormsServiceProvider;

class Directorist extends IntegrationBase {
	public function boot(): void {
		add_action( 'atbdp_before_login_form_end', [$this, 'add_form'] );
		add_action( 'atbdp_before_user_registration_submit', [$this, 'add_form'] );
	}

	public function add_form() {
		( new LoginFormsServiceProvider() )->login_buttons( false, true, false );
	}
}