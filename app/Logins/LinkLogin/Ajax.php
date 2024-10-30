<?php
/**
 * Login Me Now Admin Ajax Base.
 *
 * @package Login Me Now
 * @since   1.0.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\LinkLogin;

use LoginMeNow\Common\AjaxCheck;
use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;
use LoginMeNow\Models\UserToken;
use LoginMeNow\Utils\Time;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Ajax {
	use Singleton;
	use Hookable;
	use AjaxCheck;

	public function __construct() {
		$this->action( 'wp_ajax_login_me_now_login_link_generate', [$this, 'login_me_now_login_link_generate'] );
		$this->action( 'wp_ajax_login_me_now_login_link_tokens', [$this, 'login_me_now_login_link_tokens'] );
		$this->action( 'wp_ajax_login_me_now_login_link_update_status', [$this, 'login_me_now_login_link_update_status'] );
		$this->action( 'wp_ajax_login_me_now_login_link_extend_time', [$this, 'login_me_now_login_link_extend_time'] );
		$this->action( 'wp_ajax_login_me_now_login_link_drop', [$this, 'login_me_now_login_link_drop'] );
		$this->action( 'wp_ajax_login_me_now_login_link_get_link', [$this, 'login_me_now_login_link_get_link'] );
	}

	public function login_me_now_login_link_generate() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$user_id     = (int) get_current_user_id();
		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		if ( ! $expiration ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		$ll   = LinkLogin::init();
		$link = $ll->create( $user_id, $expiration );
		if ( ! $link ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $link );
		wp_die();
	}

	public function login_me_now_login_link_tokens() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$offset = (int) $_POST['offset'] ?? 0;
		$limit  = (int) $_POST['limit'] ?? 10;
		$tokens = UserToken::init()->get_all( $offset, $limit );

		if ( ! is_array( $tokens ) || ! $tokens ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $tokens );
		wp_die();
	}

	public function login_me_now_login_link_update_status() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id = (int) $_POST['umeta_id'] ?? 0;
		$status   = (string) $_POST['status'] ?? 'pause';

		if ( ! $umeta_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = UserToken::init()->update_status( $umeta_id, $status );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function login_me_now_login_link_extend_time() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id    = (int) $_POST['umeta_id'] ?? 0;
		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		if ( ! $umeta_id || ! $expiration ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = UserToken::init()->extend_time( $umeta_id, $expiration );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function login_me_now_login_link_drop() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id = (int) $_POST['umeta_id'] ?? 0;
		if ( ! $umeta_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$deleted = UserToken::init()->drop( $umeta_id );

		wp_send_json_success( $deleted );
		wp_die();
	}

	public function login_me_now_login_link_get_link() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id = (int) $_POST['umeta_id'] ?? 0;
		if ( ! $umeta_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$link = LinkLogin::init()->get( $umeta_id );

		wp_send_json_success( $link );
		wp_die();
	}
}