<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Admin;

use LoginMeNow\Common\AjaxCheck;
use LoginMeNow\Repositories\SettingsRepository;

class Controller {
	use AjaxCheck;

	public function settings_typewise(): array {
		return apply_filters(
			'login_me_now_admin_settings_datatypes',
			[

				'logs'                   => 'bool',

				'dm_advance_share'       => 'bool',
				'dm_express_login_wc'    => 'bool',
				'dm_express_login_edd'   => 'bool',
				'dm_express_login_email' => 'bool',
				'dm_otp_login'           => 'bool',

				'social_login'           => 'bool',
				'user_switching'         => 'bool',
				'temporary_login'        => 'bool',
				'activity_logs'          => 'bool',
				
				'enable_sign_in_google'  => 'bool',
				'enable_sign_in_twitter' => 'bool',
				'login_layout'           => 'string',
				'login_button_style'     => 'string',
			]
		);
	}

	public function settings_update() {
		$error = $this->check_permissions( 'login_me_now_update_admin_setting', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$get_bool_settings = $this->settings_typewise();
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$sub_option_key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
		/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$sub_option_value = '';

		// @codingStandardsIgnoreStart
		if ( isset( $get_bool_settings[$sub_option_key] ) ) {
			if ( 'bool' === $get_bool_settings[$sub_option_key] ) {
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$val = isset( $_POST['value'] ) && 'true' === sanitize_text_field( $_POST['value'] ) ? true : false;
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$sub_option_value = $val;
			} else {
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$val = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
				/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$sub_option_value = $val;
			}
		}
		// @codingStandardsIgnoreEnd

		SettingsRepository::update( $sub_option_key, $sub_option_value );

		$response_data = [
			'message' => __( 'Successfully saved data!', 'login-me-now' ),
		];

		wp_send_json_success( $response_data );
	}
}