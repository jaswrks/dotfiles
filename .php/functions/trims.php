<?php
/**
 * MB trims.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

/**
 * Trim (multibyte-safe).
 *
 * @since 2018-04-29
 *
 * @param  mixed  $value        Any input value.
 * @param  string $chars        Defaults to: " \r\n\t\0\x0B".
 * @param  string $extra_chars  Additional chars to trim.
 * @param  string $side         One of `l` or `r`. Default is `lr` (both).
 *
 * @return string|array|object  Trimmed value.
 */
function trim( $value, string $chars = '', string $extra_chars = '', string $side = 'lr' ) {
	if ( is_array( $value ) || is_object( $value ) ) {
		foreach ( $value as &$_value ) {
			$_value = trim( $_value, $chars, $extra_chars, $side );
		}
		return $value;
	}

	$string = (string) $value;

	if ( ! isset( $string[0] ) ) {
		return $string;
	}

	$chars = isset( $chars[0] ) ? $chars : " \r\n\t\0\x0B";
	$chars = esc_regex( $chars . $extra_chars );

	switch ( $side ) {
		case 'l':
			return preg_replace( '/^[' . $chars . ']+/u', '', $string );

		case 'r':
			return preg_replace( '/[' . $chars . ']+$/u', '', $string );

		case 'lr':
		default:
			return preg_replace( '/^[' . $chars . ']+|[' . $chars . ']+$/u', '', $string );
	}
}

/**
 * Trim (multibyte-safe) on left.
 *
 * @since 2018-04-29
 *
 * @param  mixed  $value       Any input value.
 * @param  string $chars       Defaults to: " \r\n\t\0\x0B".
 * @param  string $extra_chars Additional chars to trim.
 *
 * @return string|array|object Trimmed value.
 */
function ltrim( $value, string $chars = '', string $extra_chars = '' ) {
	return trim( $value, $chars, $extra_chars, 'l' );
}

/**
 * Trim (multibyte-safe) on right.
 *
 * @since 2018-04-29
 *
 * @param  mixed  $value       Any input value.
 * @param  string $chars       Defaults to: " \r\n\t\0\x0B".
 * @param  string $extra_chars Additional chars to trim.
 *
 * @return string|array|object Trimmed value.
 */
function rtrim( $value, string $chars = '', string $extra_chars = '' ) {
	return trim( $value, $chars, $extra_chars, 'r' );
}

