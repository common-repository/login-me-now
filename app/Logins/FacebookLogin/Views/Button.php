<?php
/**
 * @author  Pluginly
 * @since   1.4.0
 * @version 1.6.0
 */
?>

<div class="lmn_facebook_login">
	<a data-action="lmn-connect" href="<?php echo \LoginMeNow\Logins\FacebookLogin\FacebookLogin::create_auth_url(); ?>" class="lmn_btn lmn_facebook_login_button"><span class="svg-bg">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1365.3 1365.3" height="20" width="20">
			<path d="M1365.3 682.7A682.7 682.7 0 10576 1357V880H402.7V682.7H576V532.3c0-171.1 102-265.6 257.9-265.6 74.6 0 152.8 13.3 152.8 13.3v168h-86.1c-84.8 0-111.3 52.6-111.3 106.6v128h189.4L948.4 880h-159v477a682.8 682.8 0 00576-674.3" fill="#fff"></path>
		</svg></span><span><?php esc_html_e( 'Continue with Facebook', 'login-me-now' );?></span>
	</a>
</div>