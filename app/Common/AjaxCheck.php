<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Common;

trait AjaxCheck {
	public function errors() {
		return [
			'permission' => __( 'Sorry, you are not allowed to do this operation.', 'login-me-now' ),
			'nonce'      => __( 'Nonce validation failed', 'login-me-now' ),
			'default'    => __( 'Sorry, something went wrong.', 'login-me-now' ),
			'invalid'    => __( 'No post data found!', 'login-me-now' ),
		];
	}

	/**
	 * Get ajax error message.
	 */
	public function get_error_msg( string $type ) {
		if ( ! isset( $this->errors()[$type] ) ) {
			$type = 'default';
		}

		return $this->errors()[$type];
	}

	private function check_permissions( string $ref, string $cap = null ) {
		$response_data = ['message' => $this->get_error_msg( 'permission' )];

		if ( $cap && ! current_user_can( $cap ) ) {
			return $response_data;
		}

		if ( empty( $_POST ) ) {
			$response_data = ['message' => $this->get_error_msg( 'invalid' )];

			return $response_data;
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( $ref, 'security', false ) ) {
			$response_data = ['message' => $this->get_error_msg( 'nonce' )];

			return $response_data;
		}

		return false;
	}
}