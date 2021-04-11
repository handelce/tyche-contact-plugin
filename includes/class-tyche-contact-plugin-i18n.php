<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://handelce.com
 * @since      1.0.0
 *
 * @package    Tyche_Contact_Plugin
 * @subpackage Tyche_Contact_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tyche_Contact_Plugin
 * @subpackage Tyche_Contact_Plugin/includes
 * @author     Handel CE <handel.ce@gmail.com>
 */
class Tyche_Contact_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tyche-contact-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
