<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              http://wpteam.kr/
* @since             1.0.0
* @package           WPteam_GoogleAdsense
*
* @wordpress-plugin
* Plugin Name:       WPteam Google Adsense
* Plugin URI:        http://wpteam.kr/wpteam-ggadsense/
* Description:       Provide Google Adsense on Selected Posttype.
* Version:           1.0.0
* Author:            WPteam
* Author URI:        http://wpteam.kr/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       wpteam-ggadsense
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
* Currently pligin version.
* Start at version 1.0.0 and use SemVer - https://semver.org
* Rename this for your plugin and update it as you release new versions.
*/
define( 'WPTEAM_GGADSENSE_VERSION', '1.0.0' );

/**
* The code that runs during plugin activation.
* This action is documented in includes/class-wpteam-ggadsense-activator.php
*/
function wpteam_base_activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpteam-ggadsense-activator.php';
	WPteam_GoogleAdsense_Activator::activate();
}

/**
* The code that runs during plugin deactivation.
* This action is documented in includes/class-wpteam-ggadsense-deactivator.php
*/
function wpteam_base_deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpteam-ggadsense-deactivator.php';
	WPteam_GoogleAdsense_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wpteam_base_activate_plugin_name' );
register_deactivation_hook( __FILE__, 'wpteam_base_deactivate_plugin_name' );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-wpteam-ggadsense.php';

/**
* Begins execution of the plugin.
*
* Since everything within the plugin is registered via hooks,
* then kicking off the plugin from this point in the file does
* not affect the page life cycle.
*
* @since    1.0.0
*/
function wpteam_base_run_plugin_name() {

	$plugin = new WPteam_GoogleAdsense();
	$plugin->run();

}
wpteam_base_run_plugin_name();
