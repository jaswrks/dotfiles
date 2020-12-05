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
 * Gets home directory.
 *
 * @since 2019-12-31
 *
 * @param  string $path Optional path.
 *
 * @return string Home directory.
 */
function home_dir( string $path = '' ) : string {
	$dir = rtrim( $_SERVER['HOME'], '\\/' ); // phpcs:ignore -- `$_SERVER` ok.
	return $dir . ( $path ? '/' . ltrim( $path, '\\/' ) : '' );
}

/**
 * Removes a directory.
 *
 * @since 2018-11-01
 *
 * @param  string $path    Path.
 * @param  bool   $recurse Recursively?
 *
 * @return bool            True if removed successfully.
 */
function rm_dir( string $path, bool $recurse = false ) : bool {
	if ( ! is_dir( $path ) ) {
		return true;
	} elseif ( ! is_writable( $path ) ) {
		return false;
	}

	if ( ! $recurse ) {
		return (bool) rmdir( $path );
	} elseif ( ! ( $opendir = opendir( $path ) ) ) { // phpcs:ignore -- assignment ok.
		return false;
	}

	while ( ( $_sub_path = readdir( $opendir ) ) !== false ) { // phpcs:ignore -- assignment ok.
		if ( in_array( $_sub_path, [ '.', '..' ], true ) ) {
			continue; // Skip dots.

		} elseif ( is_dir( $path . '/' . $_sub_path ) ) {
			if ( ! rm_dir( $path . '/' . $_sub_path, $recurse ) ) {
				return false;
			}
		} elseif ( ! unlink( $path . '/' . $_sub_path ) ) {
			return false;
		}
	}

	closedir( $opendir );
	unset( $opendir, $_sub_path );

	return (bool) rmdir( $path );
}

/**
 * Directory regex iterator.
 *
 * @since 2018-11-01
 *
 * @param  string $path  Path.
 * @param  string $regex Regex.
 *
 * @return \RegexIterator instance.
 *
 * @throws \Exception On invalid parameters.
 */
function dir_regex_iterator( string $path, string $regex ) : \RegexIterator {
	if ( ! is_dir( $path ) || ! $regex ) {
		throw new \Exception( 'Missing required `$path`, `$regex` parameters.' );
	}

	$iterator = new \RecursiveDirectoryIterator( $path, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF | \FilesystemIterator::UNIX_PATHS );
	$iterator = new \RecursiveIteratorIterator( $iterator, \RecursiveIteratorIterator::CHILD_FIRST );
	$iterator = new \RegexIterator( $iterator, $regex, \RegexIterator::MATCH, \RegexIterator::USE_KEY );

	return $iterator;
}
