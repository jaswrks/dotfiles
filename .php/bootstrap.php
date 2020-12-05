<?php
/**
 * Bootstrap.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

error_reporting( E_ALL & ~E_DEPRECATED ); // phpcs:ignore -- ok.
ini_set( 'display_errors', 'yes' ); // phpcs:ignore -- ok.

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/vendor/autoload.php';
