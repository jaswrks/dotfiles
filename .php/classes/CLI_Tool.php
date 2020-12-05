<?php
/**
 * CLI Tool.
 *
 * @since 2018-04-29
 *
 * @package Dotfiles
 */

declare( strict_types=1 );

namespace Dotfiles;

use GetOpt\{
	GetOpt as Parser,
	Option,
	Operand,
	Command,
};
use Chalk\{
	Chalk,
	Style,
	Color as FgColor,
	BackgroundColor as BgColor,
};

/**
 * CLI Tool.
 *
 * @since 2018-04-29
 */
abstract class CLI_Tool {
	/**
	 * Tool name.
	 *
	 * @since 2018-04-29
	 *
	 * @var string
	 */
	const NAME = 'CLI Tool';

	/**
	 * Current version.
	 *
	 * @since 2018-04-29
	 *
	 * @var string
	 */
	const VERSION = '0.0.0';

	/**
	 * Parser.
	 *
	 * @since 2018-04-29
	 *
	 * @var Parser
	 */
	protected $parser;

	/**
	 * Constructor.
	 *
	 * @since 2018-04-29
	 */
	public function __construct() {
		$this->parser = new Parser( null, [
			Parser::SETTING_STRICT_OPTIONS  => true,
			Parser::SETTING_STRICT_OPERANDS => true,
		] );

		$this->add_options( [
			'help'    => [ 'description' => 'Get help.' ],
			'version' => [ 'description' => 'Show version.' ],
		] );
	}

	/**
	 * Adds commands.
	 *
	 * @since 2018-04-29
	 *
	 * @param array $commands Command configurations.
	 */
	protected function add_commands( array $commands ) : void {
		foreach ( $commands as $command => $config ) {
			$this->parser->addCommand(
				Command::create( $command, $config['callback'] ?? [ $this, str_replace( '-', '_', $command ) ] )
					->setShortDescription( $config['synopsis'] ?? '' )
					->setDescription( $config['description'] ?? '' )
					->addOptions( $this->build_options( $config['options'] ?? [] ) )
					->addOperands( $this->build_operands( $config['operands'] ?? [] ) )
			);
		}
	}

	/**
	 * Adds options.
	 *
	 * @since 2018-04-29
	 *
	 * @param array $options Option configurations.
	 */
	protected function add_options( array $options ) : void {
		$this->parser->addOptions( $this->build_options( $options ) );
	}

	/**
	 * Gets an option.
	 *
	 * @since 2018-11-01
	 *
	 * @param  string $option Option name.
	 *
	 * @return mixed Option value.
	 */
	protected function get_option( string $option ) {
		return $this->parser->getOption( $option );
	}

	/**
	 * Gets all options.
	 *
	 * @since 2018-11-01
	 *
	 * @return array All options.
	 */
	protected function get_options() : array {
		return $this->parser->getOptions();
	}

	/**
	 * Adds operands.
	 *
	 * @since 2018-04-29
	 *
	 * @param array $operands Operand configurations.
	 */
	protected function add_operands( array $operands ) : void {
		$this->parser->addOperands( $this->build_operands( $operands ) );
	}

	/**
	 * Gets an operand.
	 *
	 * @since 2018-11-01
	 *
	 * @param  string $operand Operand name.
	 *
	 * @return mixed Operand value.
	 */
	protected function get_operand( string $operand ) {
		return $this->parser->getOperand( $operand );
	}

	/**
	 * Gets all operands.
	 *
	 * @since 2018-11-01
	 *
	 * @return array All operands.
	 */
	protected function get_operands() : array {
		return $this->parser->getOperands();
	}

	/**
	 * Builds options.
	 *
	 * @since 2018-04-29
	 *
	 * @param  array $_options Option configurations.
	 *
	 * @return Option[]        An array of option instances.
	 */
	protected function build_options( array $_options ) : array {
		foreach ( $_options as $option => $config ) {
			$options[ $option ] = Option::create(
				$config['short'] ?? null,
				$config['long'] ?? $option,
				( ! empty( $config['multiple'] ) ? Parser::MULTIPLE_ARGUMENT :
					( ! empty( $config['required'] ) ? Parser::REQUIRED_ARGUMENT :
						( ! empty( $config['optional'] ) ? Parser::OPTIONAL_ARGUMENT :
							Parser::NO_ARGUMENT
						)
					)
				)
			);
			$options[ $option ]->setDescription( $config['description'] ?? '' );
			$options[ $option ]->setValidation( $config['validator'] ?? '__true' );

			if ( empty( $config['required'] ) && isset( $config['default'] ) ) {
				$options[ $option ]->setDefaultValue( $config['default'] );
			}
		}

		return $options ?? [];
	}

	/**
	 * Builds operands.
	 *
	 * @since 2018-04-29
	 *
	 * @param  array $_operands Operand configurations.
	 *
	 * @return Operand[]        An array of operand instances.
	 */
	protected function build_operands( array $_operands ) : array {
		foreach ( $_operands as $operand => $config ) {
			$operands[ $operand ] = Operand::create(
				$operand,
				( ! empty( $config['multiple'] ) && ! empty( $config['required'] ) ? Operand::MULTIPLE | Operand::REQUIRED :
					( ! empty( $config['multiple'] ) ? Operand::MULTIPLE :
						( ! empty( $config['required'] ) ? Operand::REQUIRED :
							( ! empty( $config['optional'] ) ? Operand::OPTIONAL :
								Operand::OPTIONAL
							)
						)
					)
				)
			);
			$operands[ $operand ]->setValidation( $config['validator'] ?? '__true' );

			if ( $config['multiple'] && ! isset( $config['default'] ) ) {
				$config['default'] = ''; // Avoids a bug in GetOpt class.
			}
			if ( isset( $config['default'] ) ) {
				$operands[ $operand ]->setDefaultValue( $config['default'] );
			}
		}

		return $operands ?? [];
	}

	/**
	 * Routes request.
	 *
	 * @since 2018-04-29
	 */
	protected function route_request() : void {
		try {
			$this->parser->process();
		} catch ( \Throwable $exception ) {
			$this->maybe_process_help_request();
			$this->maybe_process_version_request();

			$this->error( $exception->getMessage() );
			$this->exit_status( 1 );
		}

		$this->maybe_process_help_request();
		$this->maybe_process_version_request();

		if ( $this->parser->hasCommands() ) {
			$command                 = $this->parser->getCommand();
			$process_command_request = $command ? $command->getHandler() : null;

			if ( ! is_callable( $process_command_request ) ) {
				$this->error( 'Please specify a command.' );
				$this->log( $this->parser->getHelpText() );
				$this->exit_status( 1 );
			}

			try {
				$process_command_request();
				$this->exit_status( 0 );
			} catch ( \Throwable $exception ) {
				$this->error( $exception->getMessage() );
				$this->exit_status( 1 );
			}
		} else {
			try {
				$this->process_request();
				$this->exit_status( 0 );
			} catch ( \Throwable $exception ) {
				$this->error( $exception->getMessage() );
				$this->exit_status( 1 );
			}
		}
	}

	/**
	 * Maybe process help request.
	 */
	protected function maybe_process_help_request() : void {
		if ( $this->parser->getOption( 'help' ) ) {
			$this->log( $this->parser->getHelpText(), 'blue' );
			$this->exit_status( 0 );
		}
	}

	/**
	 * Maybe process version request.
	 */
	protected function maybe_process_version_request() : void {
		if ( $this->parser->getOption( 'version' ) ) {
			$this->log( sprintf( '%s: %s', $this::NAME, $this::VERSION ), 'blue' );
			$this->exit_status( 0 );
		}
	}

	/**
	 * Process request.
	 *
	 * @since 2018-04-29
	 */
	protected function process_request() : void {
		return; // Extenders can implement.
	}

	/**
	 * Exit with status code.
	 *
	 * @since 2018-04-29
	 *
	 * @param int $status Status code. Default is `1`.
	 */
	protected function exit_status( int $status = 0 ) : void {
		exit( $status ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Chalks (colorizes) data.
	 *
	 * @since 2018-04-29
	 *
	 * @param mixed  $data  Data.
	 * @param string $color Color.
	 *
	 * @return string Chalked string.
	 */
	protected function chalk( $data, string $color = 'none' ) : string {
		$string = $this->stringify_data( $data );
		$color  = $this->get_fg_color_code( $color );

		return Chalk::style( $string, $color );
	}

	/**
	 * Outputs a log entry.
	 *
	 * @since 2018-04-29
	 *
	 * @param mixed  $data  Output data.
	 * @param string $color Output color. Default is `none`.
	 */
	protected function log( $data, string $color = 'none' ) : void {
		$string = $this->stringify_data( $data );

		stream_set_blocking( STDOUT, true );
		fwrite( STDOUT, $this->chalk( $string, $color ) . "\n" );
	}

	/**
	 * Outputs an error.
	 *
	 * @since 2018-04-29
	 *
	 * @param mixed  $data  Output data.
	 * @param string $color Output color. Default is `red`.
	 */
	protected function error( $data, string $color = 'red' ) : void {
		$string = $this->stringify_data( $data );

		stream_set_blocking( STDERR, true );
		fwrite( STDERR, $this->chalk( $string, $color ) . "\n" );
	}

	/**
	 * Outputs an error.
	 *
	 * @since 2018-04-29
	 *
	 * @param mixed  $data  Output data.
	 * @param string $color Output color. Default is `green`.
	 */
	protected function success( $data, string $color = 'green' ) : void {
		$string = $this->stringify_data( $data );

		stream_set_blocking( STDERR, true );
		fwrite( STDERR, $this->chalk( $string, $color ) . "\n" );
	}

	/**
	 * Outputs raw data (no chalk).
	 *
	 * @since 2018-04-29
	 *
	 * @param mixed $data Output data.
	 */
	protected function output( $data ) : void {
		$string = $this->stringify_data( $data );

		stream_set_blocking( STDOUT, true );
		fwrite( STDOUT, $string . "\n" );
	}

	/**
	 * Converts data to a string.
	 *
	 * @since 2018-04-29
	 *
	 * @param  mixed $data Data.
	 *
	 * @return string String representation.
	 */
	protected function stringify_data( $data ) : string {
		if ( is_scalar( $data ) ) {
			$string = (string) $data;
		} else {
			$string = json_encode( $data, JSON_PRETTY_PRINT );
		}

		return $string;
	}

	/**
	 * Gets a style code.
	 *
	 * @since 2018-04-29
	 *
	 * @param  string $style Style name.
	 *
	 * @return string Style code.
	 */
	protected function get_style_code( string $style ) : string {
		switch ( strtolower( $style ) ) {
			case 'bold':
				return Style::BOLD;
			case 'dim':
				return Style::DIM;
			case 'underline':
				return Style::UNDERLINED;
			case 'blink':
				return Style::BLINK;
			case 'invert':
				return Style::INVERTED;
			case 'hide':
				return Style::HIDDEN;
			default:
				return Style::NONE;
		}
	}

	/**
	 * Gets a foreground color code.
	 *
	 * @since 2018-04-29
	 *
	 * @param  string $color Color name.
	 *
	 * @return string Foreground color code.
	 */
	protected function get_fg_color_code( string $color ) : string {
		switch ( strtolower( $color ) ) {
			case 'black':
				return FgColor::BLACK;
			case 'red':
				return FgColor::RED;
			case 'green':
				return FgColor::GREEN;
			case 'yellow':
				return FgColor::YELLOW;
			case 'blue':
				return FgColor::BLUE;
			case 'magenta':
				return FgColor::MAGENTA;
			case 'cyan':
				return FgColor::CYAN;
			case 'light-gray':
				return FgColor::LIGHT_GRAY;
			case 'dark-gray':
				return FgColor::DARK_GRAY;
			case 'light-red':
				return FgColor::LIGHT_RED;
			case 'light-green':
				return FgColor::LIGHT_GREEN;
			case 'light-yellow':
				return FgColor::LIGHT_YELLOW;
			case 'light-blue':
				return FgColor::LIGHT_BLUE;
			case 'light-magenta':
				return FgColor::LIGHT_MAGENTA;
			case 'light-cyan':
				return FgColor::LIGHT_CYAN;
			case 'white':
				return FgColor::WHITE;
			default:
				return FgColor::NONE;
		}
	}

	/**
	 * Gets a background color code.
	 *
	 * @since 2018-04-29
	 *
	 * @param  string $color Color name.
	 *
	 * @return string Background color code.
	 */
	protected function get_bg_color_code( string $color ) : string {
		switch ( strtolower( $color ) ) {
			case 'black':
				return BgColor::BLACK;
			case 'red':
				return BgColor::RED;
			case 'green':
				return BgColor::GREEN;
			case 'yellow':
				return BgColor::YELLOW;
			case 'blue':
				return BgColor::BLUE;
			case 'magenta':
				return BgColor::MAGENTA;
			case 'cyan':
				return BgColor::CYAN;
			case 'light-gray':
				return BgColor::LIGHT_GRAY;
			case 'dark-gray':
				return BgColor::DARK_GRAY;
			case 'light-red':
				return BgColor::LIGHT_RED;
			case 'light-green':
				return BgColor::LIGHT_GREEN;
			case 'light-yellow':
				return BgColor::LIGHT_YELLOW;
			case 'light-blue':
				return BgColor::LIGHT_BLUE;
			case 'light-magenta':
				return BgColor::LIGHT_MAGENTA;
			case 'light-cyan':
				return BgColor::LIGHT_CYAN;
			case 'white':
				return BgColor::WHITE;
			default:
				return BgColor::NONE;
		}
	}
}
