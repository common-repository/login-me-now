<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */
namespace LoginMeNow\Models;

use LoginMeNow\Common\Singleton;
use LoginMeNow\Utils\Time;
use LoginMeNow\Utils\Translator;
use LoginMeNow\Utils\User;

class UserToken {
	use Singleton;

	public $token_key = 'lmn_token';

	public function insert( int $user_id, array $data ): bool {
		return add_user_meta( $user_id, $this->token_key, $data );
	}

	public function update( int $meta_id, array $value ): bool {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$value['last_updated'] = Time::now();
		$value['updated_by']   = get_current_user_id();

		$updated = $wpdb->update(
			$table,
			['meta_value' => serialize( $value )],
			['umeta_id' => $meta_id]
		);

		return $updated;
	}

	public function get( int $meta_id ) {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_value FROM $table WHERE umeta_id  = %s",
				$meta_id
			)
		);

		return $results;
	}

	public function drop( int $meta_id ): bool {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$deleted = $wpdb->delete(
			$table,
			['umeta_id' => $meta_id]
		);

		return $deleted;
	}

	public function update_status( int $meta_id, string $status ): bool {
		if ( ! $meta_id || ! $status ) {
			return false;
		}

		$meta_value = $this->get( $meta_id )[0]->meta_value ?? null;

		if ( ! $meta_value ) {
			return false;
		}

		$status               = 'pause' === $status ? 'pause' : 'active';
		$meta_value           = maybe_unserialize( $meta_value );
		$meta_value['status'] = $status;

		return $this->update( $meta_id, $meta_value );
	}

	public function extend_time( int $meta_id, int $timestamp ): bool {
		if ( ! $meta_id || ! $timestamp ) {
			return false;
		}

		$meta_value = $this->get( $meta_id )[0]->meta_value ?? null;

		if ( ! $meta_value ) {
			return false;
		}

		$meta_value           = maybe_unserialize( $meta_value );
		$meta_value['expire'] = $timestamp;

		return $this->update( $meta_id, $meta_value );
	}

	public function get_all( int $offset = 0, int $limit = 10 ): array {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table WHERE meta_key = %s LIMIT %d OFFSET %d",
				$this->token_key,
				$limit,
				$offset
			)
		);

		foreach ( $results as $key => $meta ) {
			$results[$key]->meta_value   = unserialize( $results[$key]->meta_value );
			$results[$key]->display_name = User::display_name( $meta->user_id );
			$results[$key]->user_login   = User::login( $meta->user_id );
		}

		return $results;
	}

	public function is_valid( int $user_id, int $number, int $expire ): bool {
		$user_meta = get_user_meta( $user_id, $this->token_key, false );

		if ( ! is_array( $user_meta ) ) {
			return false;
		}

		foreach ( $user_meta as $token ) {
			$_number = (int) $token['number'] ?? 0;
			$_expire = (int) $token['expire'] ?? 0;
			$status  = $token['status'] ?? '';

			if (
				$_number === $number
				&& $_expire === $expire
				&& 'pause' !== $status
				&& ! Time::expired( $token['expire'] )
			) {
				return true;
			}
		}

		return false;
	}

	public function verify( string $token ): int {
		if ( ! $token ) {
			return false;
		}

		$data    = Translator::decode( $token );
		$user_id = (int) $data[0] ?? 0;
		$number  = (int) $data[1] ?? 0;
		$expire  = (int) $data[2] ?? 0;

		if ( ! $user_id || ! $number || ! $expire ) {
			return false;
		}

		if ( ! self::is_valid( $user_id, $number, $expire ) ) {
			return false;
		}

		return (int) $user_id;
	}
}