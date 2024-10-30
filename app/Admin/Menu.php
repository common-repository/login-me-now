<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Admin;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;

/**
 * Administration Menu Class
 */
class Menu {
	use Singleton;
	use Hookable;

	public function __construct() {
		$this->action( 'admin_menu', [$this, 'admin_menu'] );

		add_filter( 'admin_head', [$this, 'add_active_class_to_admin_menu'], 10 );
	}

	function add_active_class_to_admin_menu() {
		$screen     = get_current_screen();
		$active_nth = '';
		$style      = '';

		if ( 'toplevel_page_login-me-now' === $screen->id ) {
			$nth = [
				'temporary-login'    => 3,
				'browser-extensions' => 4,
				'social-login'       => 5,
			];

			$path       = $_GET['path'] ?? '';
			$active_nth = $nth[$path] ?? '';
		}

		if ( $active_nth ) {
			$style = '#toplevel_page_login-me-now .wp-submenu li:nth-child(' . $active_nth . ') a {
					color: #fff;
					font-weight: 600;
				}';

			$style .= '#toplevel_page_login-me-now .wp-submenu li:nth-child(2) a {
					color: rgba(240,246,252,.7) !important;
					font-weight: normal;

				}';
		}
		if ( ! defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
			echo '<style>
				#toplevel_page_login-me-now .wp-submenu li:nth-child(6) a {
					font-weight: 600;
					background-color: #93003f;
					color: #fff;
					margin: 3px 10px 0;
					display: block;
					text-align: center;
					border-radius: 3px;
					transition: all .3s;
				}

				#toplevel_page_login-me-now .wp-submenu li:nth-child(6) a:hover {
					background-color: #c60055;
    				box-shadow: none;
				}

				' . $style . '
			</style>';
		}
	}

	public function admin_menu(): void {
		$login_me_now_icon = apply_filters( 'menu_icon', 'dashicons-admin-generic' );

		if ( ! current_user_can( LOGIN_ME_NOW_MENU_CAPABILITY ) ) {
			return;
		}

		add_menu_page(
			__( 'Login Me Now', 'login-me-now' ),
			apply_filters( 'login_me_now_title', 'Login Me Now' ),
			LOGIN_ME_NOW_MENU_CAPABILITY,
			LOGIN_ME_NOW_MENU_SLUG,
			[$this, 'render_admin_dashboard'],
			$login_me_now_icon,
		);

		add_submenu_page(
			LOGIN_ME_NOW_MENU_SLUG,
			__( 'Dashboard', 'login-me-now' ),
			__( 'Dashboard', 'login-me-now' ),
			LOGIN_ME_NOW_MENU_CAPABILITY,
			LOGIN_ME_NOW_MENU_SLUG,
			[$this, 'render_admin_dashboard'],
		);

		add_submenu_page(
			LOGIN_ME_NOW_MENU_SLUG,
			__( 'Temporary Login', 'login-me-now' ),
			__( 'Temporary Login', 'login-me-now' ),
			LOGIN_ME_NOW_MENU_CAPABILITY,
			LOGIN_ME_NOW_MENU_SLUG . '&path=temporary-login',
			[$this, 'render_admin_dashboard'],
		);

		add_submenu_page(
			LOGIN_ME_NOW_MENU_SLUG,
			__( 'Browser Extension', 'login-me-now' ),
			__( 'Browser Extension', 'login-me-now' ),
			LOGIN_ME_NOW_MENU_CAPABILITY,
			LOGIN_ME_NOW_MENU_SLUG . '&path=browser-extensions',
			[$this, 'render_admin_dashboard'],
		);

		add_submenu_page(
			LOGIN_ME_NOW_MENU_SLUG,
			__( 'Social Login', 'login-me-now' ),
			__( 'Social Login', 'login-me-now' ),
			LOGIN_ME_NOW_MENU_CAPABILITY,
			LOGIN_ME_NOW_MENU_SLUG . '&path=social-login',
			[$this, 'render_admin_dashboard'],
		);

		if ( ! defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
			add_submenu_page(
				LOGIN_ME_NOW_MENU_SLUG,
				__( 'Upgrade', 'login-me-now' ),
				__( 'Upgrade', 'login-me-now' ),
				LOGIN_ME_NOW_MENU_CAPABILITY,
				LOGIN_ME_NOW_MENU_SLUG . '-upgrade-to-pro',
				[$this, 'render_admin_dashboard']
			);

			// Rewrite the menu item.
			global $submenu;
			$submenu[LOGIN_ME_NOW_MENU_SLUG][4][2] = 'https://loginmenow.com/pricing/';
		} else {
			add_submenu_page(
				LOGIN_ME_NOW_MENU_SLUG,
				__( 'License', 'login-me-now' ),
				__( 'License', 'login-me-now' ),
				LOGIN_ME_NOW_MENU_CAPABILITY,
				LOGIN_ME_NOW_MENU_SLUG . '&path=license',
				[$this, 'render_admin_dashboard']
			);
		}
	}

	/**
	 * Renders the admin settings.
	 */
	public function render_admin_dashboard(): void {
		$page_action = '';

		if ( isset( $_GET['action'] ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = str_replace( '_', '-', $page_action );
		}

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		include_once LOGIN_ME_NOW_TEMPLATE_PATH . '/admin/base.php';
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
	}
}