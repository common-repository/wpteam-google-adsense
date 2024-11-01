<?php

/**
* Define the internationalization functionality
*
* Loads and defines the internationalization files for this plugin
* so that it is ready for translation.
*
* @link       http://wpteam.kr
* @since      1.0.0
*
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/includes
*/

/**
* Define the internationalization functionality.
*
* Loads and defines the internationalization files for this plugin
* so that it is ready for translation.
*
* @since      1.0.0
* @package    WPteam_GoogleAdsense
* @subpackage WPteam_GoogleAdsense/includes
* @author     WPteam <mins9919@naver.com>
*/
class WPteam_GoogleAdsense_i18n {


	/**
	* Load the plugin text domain for translation.
	*
	* @since    1.0.0
	*/
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpteam-ggadsense',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
