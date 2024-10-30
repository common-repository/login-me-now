<?php
/**
 * @author 	Pluginly
 * @since	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Integrations\SimpleHistory;

use LoginMeNow\Common\IntegrationBase;

class SimpleHistory extends IntegrationBase {
	public function boot(): void {
		if ( ! self::enabled() ) {
			return;
		}

		add_filter( 'simple_history/row_sender_image_output', [$this, 'add_image'], 10, 2 );
	}

	public function add_image( $sender_image_html, $row ) {
		if ( 'Login Me Now' === $row->initiator ) {
			return "<img height='32px' width = '32px' alt='LoginMeNow' src='" . LOGIN_ME_NOW_ADMIN_URL . "/images/icon.svg' >";
		}

		return $sender_image_html;
	}

	public static function enabled(): bool {
		if (
			! function_exists( "SimpleLogger" )
			|| ! \LoginMeNow\Utils\Module::is_active( 'activity_logs' )
		) {
			return false;
		}

		return true;
	}
}