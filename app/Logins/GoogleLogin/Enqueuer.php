<?php
/**
 * @author  Pluginly
 * @since   1.0.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Common\EnqueuerBase;
use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Common\Hookable;

/**
 * Assets Enqueuer
 */
class Enqueuer extends EnqueuerBase {
	use Hookable;

	public function __construct() {
		$this->action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'], 50 );
		$this->action( 'login_enqueue_scripts', [$this, 'enqueue_scripts'], 1 );

		$this->action( 'wp_footer', [$this, 'credential'], 50 );
		$this->action( 'login_footer', [$this, 'credential'], 50 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'login-me-now-google-client-js', '//accounts.google.com/gsi/client' );
	}

	public function credential() {
		global $wp;
		$nonce          = wp_create_nonce( 'lmn-google-nonce' );
		$client_id      = SettingsRepository::get( 'google_client_id' );
		$cancel_outside = SettingsRepository::get( 'google_cancel_on_tap_outside', true );
		$current_url    = home_url( add_query_arg( [], $wp->request ) );
		$login_uri      = home_url() . '/?lmn-google';
		$show_onetap    = apply_filters( 'login_me_now_google_login_show_onetap', true );
		?>

		<div id="g_id_onload"
			data-client_id="<?php echo esc_attr( $client_id ); ?>"
			data-wpnonce="<?php echo esc_attr( $nonce ); ?>"
			data-redirect_uri="<?php echo esc_attr( $current_url ); ?>"
			data-login_uri="<?php echo esc_attr( $login_uri ); ?>"

			data-auto_prompt="<?php echo $show_onetap ? 'true' : 'false'; ?>"

			<?php if ( $show_onetap ): ?>
				data-context=""
				data-itp_support="true"
				data-cancel_on_tap_outside="<?php echo esc_attr( $cancel_outside ? 'true' : 'false' ); ?>"
			<?php endif;?>
			>
		</div>
	<?php
}
}