<?php
/**
 * Functions.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

foreach ( glob( __DIR__ . '/functions/*.php' ) as $file ) {
	require_once $file;
}
