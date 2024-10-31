<?php
/*
Plugin Name: Pop-up maker for CF7
Version: 1.0.3
Author: komanda.dev
Author URI: https://komanda.dev/
Description: Pop-up maker for CF7
Requires PHP: 7.3
Requires at least: 5.9.3
Text Domain: pop-up-maker-for-cf7
Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Hey! this not allowed' );
}

use PopUpMakerForCF7\basic\storage\storage;

class PopUpMakerForCF7 {
	/**
	 * @var mixed
	 */
	public static $debug = false;

	public static function init() {
		self::$debug = self::$debug && is_writable( __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR );

		if ( self::$debug ) {
			set_error_handler( array( __CLASS__, 'error_handler' ) );
			set_exception_handler( array( __CLASS__, 'error_handler_exception' ) );
		}

		spl_autoload_register( array( __CLASS__, 'autoload' ) );

		$storage = new storage(
			array(
				'RAM' => array(
					'PLUGIN_NAME' => 'pop_up_maker_for_cf7',
					'PLUGIN_CLASS' => __CLASS__,
					'PLUGIN_FILE' => __FILE__,
					'PLUGIN_DIR' => __DIR__,
					'PLUGIN_URL' => plugins_url( '', __FILE__ ),
				),
				'DB' => array(
					'table_name' => 'pop_up_maker_for_cf7_table',
				),
			)
		);

		if ( method_exists( 'PopUpMakerForCF7\basic\plugin', 'init' ) ) {
			call_user_func_array( 'PopUpMakerForCF7\basic\plugin::init', array( $storage ) );
		}

		$plugins_name = $storage->get( 'PLUGIN_NAME' );
		do_action( $plugins_name . '_init' );

		if ( self::$debug ) {
			restore_error_handler();
			restore_exception_handler();
		}

	}

	/**
	 * @param  $class_name
	 * @return null
	 */
	public static function autoload( $class_name ) {
		$re = '/^(PopUpMakerForCF7)\\\\(.*)/';
		preg_match( $re, $class_name, $matches, PREG_OFFSET_CAPTURE, 0 );
		$is_plugin_namespace = isset( $matches[1] );

		if ( ! $is_plugin_namespace ) {
			return;
		}

		$class_name = $matches[2][0];

		$path = __DIR__ . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . str_replace( array( '\\', DIRECTORY_SEPARATOR ), DIRECTORY_SEPARATOR, $class_name ) . '.php';

		if ( file_exists( $path ) ) {
			include $path;
		}

	}

	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 */
	public static function error_handler( $errno, $errstr = '', $errfile = '', $errline = '' ) {
		$time_zone = new DateTimeZone( 'Europe/Kiev' );
		$now = new DateTime( 'now', $time_zone );
		$date_format_hour = $now->format( 'Y_m_d--H' );
		$date_format_sec = $now->format( 'Y_m_d--H_i_s' );

		$subdir = 'standart';

		if ( 'autoload' === $errno ) {
			$subdir = 'autoload';
		} elseif ( 'exception' === $errno ) {
			$subdir = 'exception';
		}

		$log_dir = __DIR__ . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $subdir;

		if ( ! file_exists( $log_dir ) ) {
			mkdir( $log_dir, 0775, true );
		}

		$filename = $date_format_hour . '.log';
		$file_path = $log_dir . DIRECTORY_SEPARATOR . $filename;
		$title = $errno . ': ' . $date_format_sec . ' | Row - ' . $errline . ' | File - ' . $errfile;
		$text = "\n ------- $title -------\n";
		$text .= $errstr . "\n";
		$text .= "\n";
		$f_open = fopen( $file_path, 'a' );
		fwrite( $f_open, $text );
		fclose( $f_open );
		return true;
	}

	/**
	 * @param $e
	 */
	public static function error_handler_exception( $e ) {
		self::error_handler( 'exception', $e->getMessage(), $e->getFile(), $e->getLine() );
	}

	/**
	 * @param $name
	 * @param $arguments
	 */
	public static function __callStatic( $name, $arguments ) {

		if ( ! method_exists( 'PopUpMakerForCF7\basic\plugin', $name ) ) {
			throw new Exception( 'method not exist in "PopUpMakerForCF7\basic\plugin"' . $name );
		}

		return call_user_func_array( 'PopUpMakerForCF7\basic\plugin::' . $name, $arguments );
	}

}

PopUpMakerForCF7::init();
