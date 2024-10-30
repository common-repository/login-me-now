<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.2
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

use LoginMeNow\Common\AjaxCheck;
use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;
use LoginMeNow\Models\BrowserTokenModel;
use LoginMeNow\Utils\Time;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Ajax {
	use Singleton;
	use Hookable;
	use AjaxCheck;

	public function __construct() {
		$this->action( 'wp_ajax_login_me_now_hide_save_to_browser_extension', [$this, 'hide_save_to_browser_extension'] );

		$this->action( 'wp_ajax_login_me_now_browser_token_generate', [$this, 'browser_token_generate'] );
		$this->action( 'wp_ajax_login_me_now_browser_tokens', [$this, 'browser_tokens'] );
		$this->action( 'wp_ajax_login_me_now_browser_token_update_status', [$this, 'browser_token_update_status'] );
		$this->action( 'wp_ajax_login_me_now_browser_token_drop', [$this, 'browser_token_drop'] );
	}

	public function hide_save_to_browser_extension() {
		wp_send_json_success(
			update_user_meta(
				get_current_user_id(),
				'login_me_now_hide_save_to_browser_extension',
				true
			)
		);
	}

	public function browser_token_generate() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		$additional_data = false;
		if ( ! empty( $_POST['additional_data'] ) ) {
			$additional_data = true;
		}

		$token = ( new JWTAuth() )->new_token( $user, $expiration, $additional_data );

		if ( ! $token ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Browser Token Successfully Generated', 'login-me-now' ),
				'link'    => $token,
			]
		);

		wp_die();
	}

	public function browser_tokens() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$offset = (int) $_POST['offset'] ?? 0;
		$limit  = (int) $_POST['limit'] ?? 10;
		$tokens = BrowserTokenModel::init()->get_all( $offset, $limit );

		if ( ! is_array( $tokens ) || ! $tokens ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $tokens );
		wp_die();
	}

	public function browser_token_update_status() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$token_id = (int) $_POST['token_id'] ?? 0;
		$status   = (string) $_POST['status'] ?? 'pause';

		if ( ! $token_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = BrowserTokenModel::init()->update( $token_id, $status );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function browser_token_drop() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$token_id = (int) $_POST['token_id'] ?? 0;
		if ( ! $token_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$deleted = BrowserTokenModel::init()->drop( $token_id );

		wp_send_json_success( $deleted );
		wp_die();
	}
}