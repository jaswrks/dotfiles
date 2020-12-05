<?php
/**
 * Escapes.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

/**
 * Escapes regex special chars.
 *
 * @since 2018-04-29
 *
 * @param  string $string    Input string.
 * @param  string $delimiter Default is `/`.
 *
 * @return string            Regex-escaped string.
 */
function esc_regex( string $string, $delimiter = '/' ) : string {
	return preg_quote( $string, $delimiter );
}
