<?php
/**
 * CLI Bootstrap.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

if ( 'cli' !== PHP_SAPI ){
	throw new \Exception( 'Requires CLI access.' );
}
require_once __DIR__ . '/bootstrap.php';
