<?php

namespace PopUpMakerForCF7\basic;

use \PopUpMakerForCF7\modules\depsChecker\depsChecker;
use \PopUpMakerForCF7\modules\form7modals\form7modals;

class plugin {

	/**
	 * @var mixed
	 */
	public static $storage;

	/**
	 * @param $storage
	 */
	public static function init( $storage ) {

		self::$storage = $storage;

		register_activation_hook( self::$storage->get( 'PLUGIN_FILE' ), array( __CLASS__, 'activation_hook' ) );
		register_deactivation_hook( self::$storage->get( 'PLUGIN_FILE' ), array( __CLASS__, 'deactivation_hook' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'front_scripts' ) );

		load_plugin_textdomain( 'pop-up-maker-for-cf7', false, basename( $storage->get( 'PLUGIN_DIR' ) ) . '/languages' );

		form7modals::init( $storage );
		depsChecker::init( $storage );

	}

	public static function activation_hook() {
		self::$storage->one_call_init();
	}

	public static function deactivation_hook() {
		self::$storage->destroy();
	}

	public static function admin_scripts() {
		$plugins_name = self::$storage->get( 'PLUGIN_NAME' );
		do_action( $plugins_name . '_admin_assets', self::$storage );
	}

	public static function front_scripts() {
		$plugins_name = self::$storage->get( 'PLUGIN_NAME' );
		do_action( $plugins_name . '_front_assets', self::$storage );
	}
}
