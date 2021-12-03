<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Integration
 * @subpackage Nextlevel_Integration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Nextlevel_Integration
 * @subpackage Nextlevel_Integration/includes
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Integration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'nextlevel-integration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
