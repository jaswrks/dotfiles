<?php
/**
 * CLI utils.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

/**
 * Gets standard input.
 *
 * @since 2018-04-29
 *
 * @param  int $lines Defaults to `0` (no limit).
 *
 * @return string     X lines of stdin.
 */
function get_stdin( int $lines = 0 ) : string {
	$stdin      = '';
	$lines_read = 0;

	// Do not wait for stdin to arrive.
	// It's either here now, or it's not available.
	stream_set_blocking( STDIN, false );

	while ( false !== ( $_line = fgets( STDIN ) ) ) {
		$stdin .= $_line;
		$lines_read++;

		if ( $lines && $lines_read >= $lines ) {
			break;
		}
	}

	return trim( $stdin );
}

/**
 * Executes a shell command (throws exception on failure).
 *
 * @param string      $cmd Command to execute.
 * @param string|null $cwd Current working directory. Default is `null`.
 *
 * @throws \Exception On non-zero exit status code.
 */
function passthru( string $cmd, string $cwd = null ) {
	$cmd = $cwd ? 'cd ' . quote_shell_arg( $cwd ) . ' && ' . $cmd : $cmd;
	\passthru( $cmd, $status );

	if ( 0 !== $status ) {
		throw new \Exception( 'Unexpected status: ' . $status );
	}
}

/**
 * Executes a shell command (throws exception on failure).
 *
 * @param string      $cmd   Command to execute.
 * @param string|null $cwd   Current working directory. Default is `null`.
 * @param string|null $stdin Stdin to send to command. Default is `null`.
 *
 * @return \StdClass         `[status, stdout, stderr]`.
 * @throws \Exception        On non-zero exit status code.
 */
function run( string $cmd, string $cwd = null, string $stdin = null ) : \StdClass {
	$response = exec( $cmd, $cwd, $stdin );

	if ( 0 !== $response->status ) {
		throw new \Exception( $response->stderr ?: $response->stdout );
	}

	return $response;
}

/**
 * Executes a shell command.
 *
 * @param string      $cmd   Command to execute.
 * @param string|null $cwd   Current working directory. Default is `null`.
 * @param string|null $stdin Stdin to send to command. Default is `null`.
 *
 * @return \StdClass         `[status, stdout, stderr]`.
 */
function exec( string $cmd, string $cwd = null, string $stdin = null ) : \StdClass {
	$response = (object) [
		'status' => 0,
		'stdout' => '',
		'stderr' => '',
	];
	$config   = [
		0 => [ 'pipe', 'r' ], // stdin.
		1 => [ 'pipe', 'w' ], // stdout.
		2 => [ 'pipe', 'w' ], // stderr.
	];
	$process  = proc_open( $cmd, $config, $pipes, $cwd );

	if ( ! is_resource( $process ) ) {
		return $response;
	}

	if ( isset( $stdin ) ) {
		fwrite( $pipes[0], $stdin );
	}
	fclose( $pipes[0] );

	stream_set_blocking( $pipes[1], true );
	$response->stdout = trim( stream_get_contents( $pipes[1] ) );
	fclose( $pipes[1] );

	stream_set_blocking( $pipes[2], true );
	$response->stderr = trim( stream_get_contents( $pipes[2] ) );
	fclose( $pipes[2] );

	$status           = proc_get_status( $process );
	$response->status = $status['exitcode'];
	proc_close( $process );

	return $response;
}
