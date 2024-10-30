<?php
/**
 * @author  Pluginly
 * @since   1.0.0
 * @version 1.6.2
 */

namespace LoginMeNow\Admin;

use LoginMeNow\Common\EnqueuerBase;
use LoginMeNow\Utils\Helper;

/**
 * Assets Enqueuer
 */
class Enqueuer extends EnqueuerBase {

	public function __construct() {
		$this->action( 'admin_init', [$this, 'settings_admin_scripts'] );
		$this->action( 'after_setup_theme', [$this, 'init_admin_settings'], 99 );
	}

	public function admin_register_scripts(): void {
		$this->register_style( 'login-me-now-admin-main', LOGIN_ME_NOW_ASSETS . '/style.css', [] );
		$this->register_script( 'login-me-now-admin-main', LOGIN_ME_NOW_ASSETS . '/main.js', [] );
	}

	public function admin_enqueue_scripts(): void {
		$this->enqueue_style( 'login-me-now-admin-main' );
		$this->enqueue_script( 'login-me-now-admin-main' );
	}

	/**
	 *  Initialize after Login Me Now gets loaded.
	 *
	 * @since 1.0.0
	 */
	public function settings_admin_scripts(): void {
		// Enqueue admin scripts.
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( ! empty( $_GET['page'] ) &&
			(
				LOGIN_ME_NOW_MENU_SLUG === $_GET['page'] ||
				false !== strpos( $_GET['page'], LOGIN_ME_NOW_MENU_SLUG . '_' )
			)
		) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$this->action( 'admin_enqueue_scripts', [$this, 'styles_scripts'] );
		}
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 1.0.0
	 */
	public function styles_scripts() {
		if ( is_customize_preview() ) {
			return;
		}

		wp_enqueue_style( 'astra-admin-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap', [], LOGIN_ME_NOW_VERSION );
		wp_enqueue_style( 'wp-components' );

		/** @psalm-suppress UndefinedClass */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$show_self_branding = true;
		/** @psalm-suppress UndefinedClass */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$localize = [
			'current_user'           => ! empty( wp_get_current_user()->user_firstname ) ? ucfirst( wp_get_current_user()->user_firstname ) : ucfirst( wp_get_current_user()->display_name ),
			'admin_base_url'         => admin_url(),
			'plugin_dir'             => LOGIN_ME_NOW_URL,
			'plugin_ver'             => defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ? LOGIN_ME_NOW_PRO_VERSION : '',
			'version'                => LOGIN_ME_NOW_VERSION,
			'pro_available'          => defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ? true : false,
			'pro_installed_status'   => 'installed' === Helper::get_plugin_status( 'login-me-now-pro/login-me-now-pro.php' ) ? true : false,
			'simple_history_status'  => 'activated' === Helper::get_plugin_status( 'simple-history/index.php' ) ? true : false,
			'product_name'           => __( 'Login Me Now', 'login-me-now' ),
			'plugin_name'            => __( 'Login Me Now PRO', 'login-me-now' ),
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'google_redirect_url'    => home_url( 'wp-login.php?lmn-google' ),
			'facebook_redirect_url'  => home_url( 'wp-login.php?lmn-facebook' ),
			'show_self_branding'     => $show_self_branding,
			'admin_url'              => admin_url( 'admin.php' ),
			'home_slug'              => LOGIN_ME_NOW_MENU_SLUG,
			'upgrade_url'            => LOGIN_ME_NOW_PRO_UPGRADE_URL,
			'extension_url'          => 'https://chrome.google.com/webstore/detail/login-me-now/kkkofomlfhbepmpiplggmfpomdnkljoh/?source=wp-dashboard',
			'login_me_now_base_url'  => admin_url( 'admin.php?page=' . LOGIN_ME_NOW_MENU_SLUG ),
			'logo_url'               => apply_filters( 'login_me_now_admin_menu_icon', LOGIN_ME_NOW_ASSETS . '/images/icon.svg' ),
			'update_nonce'           => wp_create_nonce( 'login_me_now_update_admin_setting' ),
			'plugin_manager_nonce'   => wp_create_nonce( 'login_me_now_plugin_manager_nonce' ),
			'generate_token_nonce'   => wp_create_nonce( 'login_me_now_generate_token_nonce' ),
			'plugin_installer_nonce' => wp_create_nonce( 'updates' ),
			'free_vs_pro_link'       => admin_url( 'admin.php?page=' . LOGIN_ME_NOW_MENU_SLUG . '&path=free-vs-pro' ),
			'plugin_installed_text'  => __( 'Installed', 'login-me-now' ),
			'plugin_activating_text' => __( 'Activating', 'login-me-now' ),
			'plugin_activated_text'  => __( 'Activated', 'login-me-now' ),
			'plugin_activate_text'   => __( 'Activate', 'login-me-now' ),
			'upgrade_notice'         => true,
			'time_zone'              => wp_timezone_string(),
		];

		$this->settings_app_scripts( apply_filters( 'login_me_now_react_admin_localize', $localize ) );
	}

	/**
	 * Settings app scripts
	 *
	 * @since 1.0.0
	 * @param array $localize Variable names.
	 */
	public function settings_app_scripts( $localize ): void {
		$handle            = 'login-me-now-admin-dashboard-app';
		$build_path        = LOGIN_ME_NOW_PATH . '/assets/';
		$script_asset_path = $build_path . 'dashboard-app.asset.php';

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$script_info = file_exists( $script_asset_path ) ? include $script_asset_path : [
			'dependencies' => [],
			'version'      => LOGIN_ME_NOW_VERSION,
		];
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$script_dep = array_merge( $script_info['dependencies'], ['updates', 'wp-hooks'] );

		wp_register_script(
			$handle,
			LOGIN_ME_NOW_ASSETS . '/dashboard-app.js',
			$script_dep,
			$script_info['version'],
			true
		);

		wp_register_style(
			$handle,
			LOGIN_ME_NOW_ASSETS . '/dashboard-app.css',
			[],
			LOGIN_ME_NOW_VERSION
		);

		wp_register_style(
			'login-me-now-admin-google-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap',
			[],
			LOGIN_ME_NOW_VERSION
		);

		wp_enqueue_script( $handle );

		wp_set_script_translations( $handle, 'login-me-now' );

		wp_enqueue_style( 'login-me-now-admin-google-fonts' );
		wp_enqueue_style( $handle );

		wp_style_add_data( $handle, 'rtl', 'replace' );

		wp_localize_script( $handle, 'lmn_admin', $localize );
	}

	/**
	 * Admin settings init.
	 */
	public function init_admin_settings() {
		$this->action( 'admin_head', [$this, 'admin_submenu_css'] );
	}

	/**
	 * Add custom CSS for admin area sub menu icons.
	 */
	public function admin_submenu_css(): void {
		echo '<style class="astra-menu-appearance-style">
				.toplevel_page_login-me-now > div.wp-menu-image:before {
					line-height: 27px !important;
					content: "";
					background: url("' . LOGIN_ME_NOW_ASSETS . '/images/sidebar.svg' . '") no-repeat center center;
					speak: none !important;
					font-style: normal !important;
					font-weight: normal !important;
					font-variant: normal !important;
					text-transform: none !important;
					/* Better Font Rendering =========== */
					-webkit-font-smoothing: antialiased !important;
					-moz-osx-font-smoothing: grayscale !important;
					box-sizing: content-box;
				}
			</style>';
	}
}