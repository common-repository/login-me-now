<?php
/**
 * @author  Pluginly
 * @since   1.5.0
 * @version 1.6.0
 */
?>

<div class="lmn_google_login">
    <a data-action="lmn-connect" href="<?php echo \LoginMeNow\Logins\GoogleLogin\GoogleLogin::create_auth_url(); ?>" class="lmn_btn lmn_google_login_button">
		<span class="svg-bg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3" height="20" width="20">
				<path fill="#4285F4" d="M533.5 278.1c0-18.5-1.5-37.1-4.6-55.3H272.1v104.8h145.4c-6.1 33.8-26.9 62.5-57.2 81.6v68.1h92.5c54.2-50.1 85.2-123.5 85.2-199.2z"/>
				<path fill="#34A853" d="M272.1 544.3c77.4 0 142.3-25.3 189.9-68.8l-92.5-68.1c-25.9 17.5-58.9 27-97.4 27-74.7 0-138.1-50.2-160.7-118.1H6.2v73.7c47.8 94.7 147.6 160.3 265.9 160.3z"/>
				<path fill="#FBBC05" d="M111.4 324.2c-9.2-27.9-9.2-58.2 0-86.1V164H18.7C7.1 197.1 0 235 0 272.1s7.1 75 18.7 108.1l92.7-68.1z"/>
				<path fill="#EA4335" d="M272.1 107.9c39.5-.9 76.3 13.4 104.4 36.8l77.7-77.7C414.3 22.4 347.1-.1 272.1 0 154 0 48.2 67.7 18.7 164l92.7 68.1c22.7-67.9 86.1-118.2 160.7-124.2z"/>
			</svg></span><span><?php esc_html_e( 'Continue with Google', 'login-me-now' );?></span>
		</a>
</div>