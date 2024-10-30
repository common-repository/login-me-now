<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Logins\UserSwitchingLogin;

use LoginMeNow\Common\Hookable;
use LoginMeNow\Common\Singleton;

class AdminNotice {
	use Hookable;
	use Singleton;

	public function __construct() {
		$this->action( 'admin_notices', [$this, 'user_switching'] );
	}

	public function user_switching() {?>
		<div class="error">
			<p>
			<?php _e( 'Since you are using Login Me Now, you can deactivate the <strong>User Switching</strong> plugin, If you are not interested to deactivate the User Switching plugin, simply turn off the `User Switching` from <strong>Dashboard -> User Switching</strong>..',
		'login-me-now' );?>
			</p>
		</div>
	<?php }
}