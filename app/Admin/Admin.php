<?php
/**
 * @author  Pluginly
 * @since   1.0.0
 * @version 1.6.2
 */

namespace LoginMeNow\Admin;

class Admin {
	public function __construct() {
		( new Ajax() );
		( new Enqueuer() );
		( new Menu() );
		( new Route() );

		add_filter( 'admin_footer_text', [$this, 'admin_footer_link'], 99 );
	}

	public function admin_footer_link() {
		if ( isset( $_GET['page'] ) && 'login-me-now' === $_GET['page'] ) {
			return '<span id="footer-thankyou"> Thank you for using <span class="focus:text-astra-hover active:text-astra-hover hover:text-lmn-hover"> ' . esc_attr( __( 'Login Me Now', 'login-me-now' ) ) . '.</span></span>';
		}
	}
}