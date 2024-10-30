<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Providers;

use LoginMeNow\Common\ProviderBase;
use LoginMeNow\Logins\FacebookLogin\Button as FacebookButton;
use LoginMeNow\Logins\FacebookLogin\FacebookLogin;
use LoginMeNow\Logins\GoogleLogin\Button as GoogleButton;
use LoginMeNow\Logins\GoogleLogin\GoogleLogin;

class LoginFormsServiceProvider extends ProviderBase {

	use \LoginMeNow\Common\Hookable;

	public function boot() {
		$this->action( 'login_footer', [$this, 'wp_login_script'], 50 );

		$this->action( 'login_form', [$this, 'login_buttons'] );
		$this->action( 'register_form', [$this, 'login_buttons'] );
		$this->filter( 'login_form_top', [$this, 'login_buttons_filter'] );

		$this->action( 'login_me_now_popup_authenticate_redirection', [$this, 'login_me_now_popup_authenticate_redirection'] );

	}

	public function login_buttons_filter() {
		return $this->login_buttons( true );
	}

	public function login_buttons( bool $return = false, $before = false, $after = true ) {
		if ( ! $this->show() ) {
			return;
		}

		$buttons = $this->buttons();

		ob_start();
		include_once LOGIN_ME_NOW_TEMPLATE_PATH . '/login-form.php';
		$html = ob_get_clean();

		if ( $return ) {
			return $html;
		}

		echo $html;
	}

	public function buttons(): array {
		$array    = [];
		$google   = new GoogleButton();
		$facebook = new FacebookButton();

		if ( $google->native_login() ) {
			$array['google'] = $google;
		}

		if ( $facebook->native_login() ) {
			$array['facebook'] = $facebook;
		}

		return $array;
	}

	public function show(): bool {
		if (
			FacebookLogin::show_on_native_login()
			|| GoogleLogin::show_on_native_login()
		) {
			return true;
		}

		return false;
	}

	public function wp_login_script() {?>
		<script type="text/javascript">
			jQuery("#wp-login-login-me-now-buttons").prependTo("#loginform");
		</script>
	<?php }

	public function login_me_now_popup_authenticate_redirection( string $redirect_uri ) {
		?>
		<!doctype html>
		<html lang=en>
		<head>
			<meta charset=utf-8>
			<title><?php _e( 'Authentication successful', 'login-me-now' );?></title>
			<script type="text/javascript">
				try {
					if (window.opener !== null && window.opener !== window) {
						var sameOrigin = true;
						try {
							var currentOrigin = window.location.protocol + '//' + window.location.hostname;
							if (window.opener.location.href.substring(0, currentOrigin.length) !== currentOrigin) {
								sameOrigin = false;
							}

						} catch (e) {
							/**
							 * Blocked cross origin
							 */
							sameOrigin = false;
						}
						if (sameOrigin) {
							var url = <?php echo wp_json_encode( $redirect_uri ); ?>;
							if (typeof window.opener.lmnRedirect === 'function') {
								window.opener.lmnRedirect(url);
							} else {
								window.opener.location = url;
							}
							window.close();
						} else {
							window.location.reload(true);
						}
					} else {
						if (window.opener === null) {
							/**
							 * Cross-Origin-Opener-Policy blocked the access to the opener
							 */
							if (typeof BroadcastChannel === "function") {
								const _lmnLoginBroadCastChannel = new BroadcastChannel('lmn_login_broadcast_channel');
								_lmnLoginBroadCastChannel.postMessage({
									action: 'redirect',
									href:<?php echo wp_json_encode( $redirect_uri ); ?>});
								_lmnLoginBroadCastChannel.close();
								window.close();
							} else {
								window.location.reload(true);
							}
						} else {
							window.location.reload(true);
						}
					}
				} catch (e) {
					window.location.reload(true);
				}
			</script>
		</head>
		<body><a href="<?php echo esc_url( $redirect_uri ); ?>"><?php echo 'Continue...'; ?></a></body>
		</html>
	<?php exit;
	}
}