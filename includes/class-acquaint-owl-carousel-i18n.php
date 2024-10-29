<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://acquaintsoft.com/
 * @since      1.0.0
 *
 * @package    Acquaint_Owl_Carousel
 * @subpackage Acquaint_Owl_Carousel/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Acquaint_Owl_Carousel
 * @subpackage Acquaint_Owl_Carousel/includes
 * @author     Itcoderr <itcoderr@gmail.com>
 */
class Acquaint_Owl_Carousel_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'acquaint-owl-carousel',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
