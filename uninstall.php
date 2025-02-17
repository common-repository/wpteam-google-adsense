<?php

/**
* Fired when the plugin is uninstalled.
*
* When populating this file, consider the following flow
* of control:
*
* - This method should be static
* - Check if the $_REQUEST content actually is the plugin name
* - Run an admin referrer check to make sure it goes through authentication
* - Verify the output of $_GET makes sense
* - Repeat with other user roles. Best directly by using the links/query string parameters.
* - Repeat things for multisite. Once for a single site in the network, once sitewide.
*
* This file may be updated more in future version of the Boilerplate; however, this is the
* general skeleton and outline for how the file should work.
*
* For more information, see the following discussion:
* https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
*
* @link       http://wpteam.kr
* @since      1.0.0
*
* @package    WPteam_GoogleAdsense
*/

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$plugin_name = 'wpteam-ggadsense';

delete_option($plugin_name);
delete_option("{$plugin_name}_setup-basic-ads");
delete_option("{$plugin_name}_setup-wc");
// for site options in Multisite
delete_site_option($plugin_name);
delete_site_option("{$plugin_name}_setup-basic-ads");
delete_site_option("{$plugin_name}_setup-wc");

unregister_setting($plugin_name, $plugin_name);

// TODO Add remove site sending back to server
