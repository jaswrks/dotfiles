<?php
/**
 * Callbacks.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 *
 * phpcs:ignoreFile
 */

declare( strict_types=1 );

/**
 * No-op.
 *
 * @since 2018-04-29
 */
function noop() : void {}

/**
 * Return true.
 *
 * @since 2018-04-29
 *
 * @return bool True.
 */
function __true() : bool {
	return true;
}

/**
 * Return false.
 *
 * @since 2018-04-29
 *
 * @return bool False.
 */
function __false() : bool {
	return false;
}

/**
 * Return null.
 *
 * @return null Null.
 */
function __null() : void {
	return;
}

/**
 * Return empty().
 *
 * @since 2018-04-29
 *
 * @return bool True if empty.
 */
function __empty( $v ) : bool {
	return empty( $v );
}

/**
 * Return !empty().
 *
 * @since 2018-04-29
 *
 * @return bool True if not empty.
 */
function __not_empty( $v ) : bool {
	return ! empty( $v );
}

