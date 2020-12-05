<?php
/**
 * Quotes.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

/**
 * Removes empty values from an array.
 *
 * @since 2018-04-29
 *
 * @param  array $_array Input array.
 * @param  bool  $recursive Recursively? Default is `false`.
 *
 * @return array         New array w/o empty values.
 */
function remove_emptys( array $_array, bool $recursive = false ) : array {
	$array = [];

	foreach ( $_array as $key => $value ) {
		if ( $recursive && is_array( $value ) ) {
			$value = remove_emptys( $value, true );
		}
		if ( ! empty( $value ) ) {
			$array[ $key ] = $value;
		}
	}

	return $array;
}

/**
 * Removes null values from an array.
 *
 * @since 2018-04-29
 *
 * @param  array $_array Input array.
 * @param  bool  $recursive Recursively? Default is `false`.
 *
 * @return array         New array w/o null values.
 */
function remove_nulls( array $_array, bool $recursive = false ) : array {
	$array = [];

	foreach ( $_array as $key => $value ) {
		if ( $recursive && is_array( $value ) ) {
			$value = remove_nulls( $value, true );
		}
		if ( null !== $value ) {
			$array[ $key ] = $value;
		}
	}

	return $array;
}

/**
 * In caSe-insensitive string array?
 *
 * @since 2018-11-01
 *
 * @param  scalar $needle   Scalar needle.
 * @param  array  $haystack Haystack array.
 * @param  bool   $strict   Strict comparison? Default is `false`.
 *
 * @return bool             True if in array.
 */
function in_cistr_array( $needle, array $haystack, bool $strict = false ) : bool {
	return in_array( mb_strtolower( (string) $needle ), array_map( 'mb_strtolower', array_map( 'strval', $haystack ) ), $strict ); // phpcs:ignore -- strict ok.
}
