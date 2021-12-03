<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.kri8it.com
 * @since             1.0.0
 * @package           Nextlevel_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       NEXTLEVEL Integration
 * Plugin URI:        https://nextlevel.thrifty.co.za
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hilton Moore
 * Author URI:        https://www.kri8it.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nextlevel-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEXTLEVEL_INTEGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nextlevel-integration-activator.php
 */
function activate_nextlevel_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-integration-activator.php';
	Nextlevel_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nextlevel-integration-deactivator.php
 */
function deactivate_nextlevel_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-integration-deactivator.php';
	Nextlevel_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nextlevel_integration' );
register_deactivation_hook( __FILE__, 'deactivate_nextlevel_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-integration.php';








add_action( 'plugins_loaded', 'check_for_update_nextlevel' );
function check_for_update_nextlevel(){

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextlevel-integration-updater.php';


      $config = array(
            'slug'               => plugin_basename( __FILE__ ),
            'proper_folder_name' => 'nextlevel-integration',
            'api_url'            => 'https://api.github.com/repos/kri8itdigital/nextlevel-integration',
            'raw_url'            => 'https://raw.github.com/kri8itdigital/nextlevel-integration/master',
            'github_url'         => 'https://github.com/kri8itdigital/nextlevel-integration',
            'zip_url'            => 'https://github.com/kri8itdigital/nextlevel-integration/archive/master.zip',
            'homepage'           => 'https://github.com/kri8itdigital/nextlevel-integration',
            'sslverify'          => true,
            'requires'           => '5.0',
            'tested'             => '5.7',
            'readme'             => 'README.md',
            'version'            => '1.0.0'
        );

        new nextlevel_integration_updater( $config );

}









/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nextlevel_integration() {

	$plugin = new Nextlevel_Integration();
	$plugin->run();

}
run_nextlevel_integration();
