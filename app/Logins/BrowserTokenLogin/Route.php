<?php
/**
 * @author  Pluginly
 * @since   1.2.0
 * @version 1.6.2
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

defined( 'ABSPATH' ) || exit;

use LoginMeNow\Logins\BrowserTokenLogin\JWTAuth;
use LoginMeNow\Logins\BrowserTokenLogin\OnetimeNumber;

class Route extends \LoginMeNow\Common\RouteBase {

	private $endpoint = 'browser-token';

	public function register_routes(): void {
		$this->post( '/generate', [JWTAuth::init(), 'generate_token'] );
		$this->post( '/validate', [JWTAuth::init(), 'validate_token'] );
		$this->post( '/generate-onetime-number', [$this, 'generate_onetime_number'] );
	}

	/**
	 * Generate the onetime number
	 */
	public function generate_onetime_number( \WP_REST_Request $request ) {
		$result = JWTAuth::init()->validate_token( $request, 'user_id' );

		if ( is_numeric( $result ) ) {
			$link = OnetimeNumber::init()->get_shareable_link( $result );
			wp_send_json_success( $link );
		}

		wp_send_json_error( ['status' => $result] );
	}

	public function permission_check( \WP_REST_Request $request ) {
		$this->request = $request;

		return true;
	}
}