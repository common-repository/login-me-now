<?php
/**
 * @author  Pluginly
 * @since   0.91
 * @version 0.91
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
	<h1><?php echo esc_html( $title ); ?></h1>
	<h4><?php echo esc_html( $message ); ?></h4>
	<a href="<?php echo site_url(); ?>">Go Back to Homepage</a>
</body>

<?php
wp_footer();