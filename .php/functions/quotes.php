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
 * Quotes a shell arg.
 *
 * @since 2018-04-29
 *
 * @param  string $string Input string.
 *
 * @return string         Shell-quoted string.
 */
function quote_shell_arg( string $string ) : string {
	return escapeshellarg( $string );
}
