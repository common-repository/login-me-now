<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Utils;

use WP_Error;
use WP_REST_Response;

class Response {

	public static function success( $data ) {
		return new WP_REST_Response( $data, 200 );
	}

	public static function error( string $error_code, $error_message, string $endpoint = '', int $status = 500, array $additional_data = [] ) {
		return self::format_error( $error_code, $error_message, $endpoint, $status, $additional_data );
	}

	public static function format_error( string $error_code, $error_message, string $endpoint = '', int $status = 500, array $additional_data = [] ) {
		$additional_data['status'] = $status;
		if ( ! empty( $endpoint ) ) {
			$additional_data['endpoint'] = $endpoint;
		}

		return new WP_Error( $error_code, $error_message, $additional_data );
	}
}