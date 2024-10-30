<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Models;

use LoginMeNow\Common\Singleton;
use LoginMeNow\Utils\Time;
use LoginMeNow\Utils\User;

class BrowserTokenModel {
	use Singleton;

	private $wpdb;
	private $table;

	public function __construct() {
		global $wpdb;
		$this->wpdb  = $wpdb;
		$this->table = $wpdb->prefix . 'login_me_now_tokens';
	}

	public function create_table(): void {
		$collate = '';

		if ( $this->wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $this->wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET {$this->wpdb->charset}";
			}

			if ( ! empty( $this->wpdb->collate ) ) {
				$collate .= " COLLATE {$this->wpdb->collate}";
			}
		}

		$table_schema = "
            CREATE TABLE IF NOT EXISTS {$this->table} (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) NOT NULL,
                `token_id` bigint(20) NOT NULL,
                `count`  bigint(20) NOT NULL,
                `expire`  bigint(20) NOT NULL,
                `status` varchar(260) DEFAULT NULL,
                `created_at` varchar(260) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `token_id` (`token_id`)
            ) ENGINE=InnoDB {$collate};
        ";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $table_schema );
	}

	public function insert( int $user_id, int $token_id, int $expire, string $status ): void {
		$created_at = Time::now();

		$this->wpdb->query(
			$this->wpdb->prepare(
				"INSERT INTO {$this->table} (user_id, token_id, expire, status, created_at)
                VALUES (%d, %s, %s, %s, %s)",
				$user_id,
				$token_id,
				$expire,
				$status,
				$created_at
			)
		);
	}

	public function drop( int $token_id ): bool {
		$deleted = $this->wpdb->delete(
			$this->table,
			['token_id' => $token_id],
			['%s']
		);

		return $deleted;
	}

	public function update( int $token_id, string $status ): bool {
		$updated = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE {$this->table}
                    SET status = %s
                    WHERE token_id = %s",
				$status,
				$token_id
			)
		);

		return $updated;
	}

	public function get_all( int $offset = 0, int $limit = 10 ): array {
		$sql = $this->wpdb->prepare(
			"SELECT * FROM {$this->table}
            ORDER BY id DESC
            LIMIT %d OFFSET %d",
			$limit,
			$offset
		);

		$results = $this->wpdb->get_results( $sql );

		foreach ( $results as $key => $meta ) {
			$results[$key]->display_name = User::display_name( $meta->user_id );
			$results[$key]->user_login   = User::login( $meta->user_id );
		}

		return $results;
	}

	public function status( int $token_id ): string {
		$record = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table}
                WHERE token_id = %s",
				$token_id
			)
		);

		$status = $record->status ?? 'invalid';

		return $status;
	}
}