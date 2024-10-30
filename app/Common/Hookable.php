<?php
/**
 * @author  Pluginly
 * @since   1.6.0
 * @version 1.6.0
 */

namespace LoginMeNow\Common;

trait Hookable {

	public function action( string $hook_name, array $callback, int $priority = 10, int $accepted_args = 1 ): void {
		add_action(
			$hook_name,
			function ( $args ) use ( $callback ) {
				$class = new $callback[0];

				return $class->{$callback[1]}( $args );
			},
			$priority,
			$accepted_args
		);
	}

	public function filter( string $hook_name, array $callback, int $priority = 10, int $accepted_args = 1 ): void {
		add_filter(
			$hook_name,
			function ( $args ) use ( $callback ) {
				$class = new $callback[0];

				return $class->{$callback[1]}( $args );
			},
			$priority,
			$accepted_args
		);
	}
}
