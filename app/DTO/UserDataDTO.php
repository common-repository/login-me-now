<?php
/**
 * @author 	Pluginly
 * @since	1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\DTO;

use LoginMeNow\Common\DTOBase;

class UserDataDTO extends DTOBase {
	public string $user_email;
	public string $first_name;
	public string $last_name;
	public string $name;
	public string $role;
	public string $user_avatar_url;
	public string $redirect_uri;
	public int $id;
	public string $display_name;
	public string $channel_name;
	public bool $update_existing_user;

	public function set_user_email( string $user_email ) {
		$this->user_email = $user_email;

		return $this;
	}

	public function get_user_email() {
		return $this->user_email;
	}

	public function set_first_name( string $first_name ) {
		$this->first_name = $first_name;

		return $this;
	}

	public function get_first_name() {
		return $this->first_name ?? '';
	}

	public function set_last_name( string $last_name ) {
		$this->last_name = $last_name;

		return $this;
	}

	public function get_last_name() {
		return $this->last_name ?? '';
	}

	public function set_role( string $role ) {
		$this->role = $role;

		return $this;
	}

	public function get_role() {
		return $this->role ?? 'subscriber';
	}

	public function set_user_avatar_url( string $user_avatar_url ) {
		$this->user_avatar_url = $user_avatar_url;

		return $this;
	}

	public function get_user_avatar_url() {
		return $this->user_avatar_url ?? '';
	}

	public function set_redirect_uri( string $redirect_uri ) {
		$this->redirect_uri = $redirect_uri;

		return $this;
	}

	public function get_redirect_uri() {
		return $this->redirect_uri ?? admin_url();
	}

	public function set_id( int $id ) {
		$this->id = $id;

		return $this;
	}

	public function get_id() {
		return $this->id;
	}

	public function set_display_name( string $display_name ) {
		$this->display_name = $display_name;

		return $this;
	}

	public function get_display_name() {
		return $this->display_name ?? $this->get_name();
	}

	public function set_name( string $name ) {
		$this->name = $name;

		return $this;
	}

	public function get_name() {
		return $this->name ?? $this->get_first_name() . ' ' . $this->get_last_name();
	}

	public function set_channel_name( string $channel_name ) {
		$this->channel_name = $channel_name;

		return $this;
	}

	public function get_channel_name() {
		return $this->channel_name ?? 'google';
	}

	public function set_update_existing_user( bool $update_existing_user = false ) {
		$this->update_existing_user = $update_existing_user;

		return $this;
	}

	public function is_update_existing_user() {
		return $this->update_existing_user;
	}
}