<?php
/**
 * @author  Pluginly
 * @since   1.7.0
 * @version 1.5.0
 */

namespace LoginMeNow\Utils;

class Translator {
	public static function encode( string...$items ): string{
		$single = "";
		foreach ( $items as $item ) {
			$single .= $item . " ";
		}

		return trim( base64_encode( $single ) );
	}

	public static function decode( string $data ): array{
		$decodedData = base64_decode( $data, true );
		$items       = explode( " ", $decodedData );

		return $items;
	}
}