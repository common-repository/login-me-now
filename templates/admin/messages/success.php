<?php
/**
 * @author  Pluginly
 * @since   1.0.0
 * @version 1.0.0
 */

wp_head();
?>
<style>
	* {
		margin-bottom: 10px !important;
	}
	body {
		height: 80vh;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}
</style>
<body class="login-me-now">
	<h1><?php esc_html_e( 'Authentication Success 🎉', 'login-me-now' );?></h1>
	<h4><?php esc_html_e( 'You are being redirected to the dashboard', 'login-me-now' );?></h4>
	<p><?php _e( 'If you are not redirected yet click', 'login-me-now' )?> <a href="<?php echo admin_url(); ?>"><?php _e( 'here', 'login-me-now' )?></a></p>
	<script>location.replace("<?php echo admin_url(); ?>")</script>
</body>

<?php
wp_footer();