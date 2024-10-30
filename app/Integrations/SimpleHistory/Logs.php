<?php
/**
 * @author 	Pluginly
 * @since	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Integrations\SimpleHistory;

class Logs {
	public static function add( int $user_id, string $message ): void {
		if ( ! SimpleHistory::enabled() ) {
			return;
		}

		$user_info             = get_userdata( $user_id );
		$username              = $user_info->user_login;
		$context               = [];
		$context['_initiator'] = 'Login Me Now';

		SimpleLogger()->info( $username . ' ' . $message, $context );
	}
}