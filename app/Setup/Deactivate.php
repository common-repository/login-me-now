<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */
namespace LoginMeNow\Setup;

class Deactivate {
	public function deactivate(): void {
		\flush_rewrite_rules();
	}
}
