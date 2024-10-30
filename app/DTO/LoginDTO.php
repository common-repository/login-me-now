<?php
/**
 * @author 	Pluginly
 * @since	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\DTO;

use LoginMeNow\Common\DTOBase;

class LoginDTO extends DTOBase {
	public int $user_id;
	public string $redirect_uri;
	public bool $redirect_return;
	public string $channel_name;

	public function set_user_id( int $user_id ) {
		$this->user_id = $user_id;

		return $this;
	}

	public function get_user_id() {
		return $this->user_id;
	}

	public function set_redirect_uri( string $redirect_uri ) {
		$this->redirect_uri = $redirect_uri;

		return $this;
	}

	public function get_redirect_uri() {
		return $this->redirect_uri;
	}

	public function set_redirect_return( bool $redirect_return ) {
		$this->redirect_return = $redirect_return;

		return $this;
	}

	public function is_redirect_return() {
		return $this->redirect_return;
	}

	public function set_channel_name( string $channel_name ) {
		$this->channel_name = $channel_name;

		return $this;
	}

	public function get_channel_name() {
		return $this->channel_name ?? '';
	}
}