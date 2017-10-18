<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.audioverse.org
 * @since             1.0.0
 * @package           Wp_Avorg_Multisite_Catalog
 *
 * @wordpress-plugin
 * Plugin Name:       AudioVerse Catalog
 * Plugin URI:        https://github.com/AVORG/wp-avorg-multisite-catalog
 * Description:       Access to the AudioVerse Catalog by tags, using WP short codes.
 * Version:           1.0.0
 * Author:            Ki Song
 * Author URI:        https://www.audioverse.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-avorg-multisite-catalog
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-avorg-multisite-catalog-activator.php
 */
function activate_wp_avorg_multisite_catalog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-avorg-multisite-catalog-activator.php';
	Wp_Avorg_Multisite_Catalog_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-avorg-multisite-catalog-deactivator.php
 */
function deactivate_wp_avorg_multisite_catalog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-avorg-multisite-catalog-deactivator.php';
	Wp_Avorg_Multisite_Catalog_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_avorg_multisite_catalog' );
register_deactivation_hook( __FILE__, 'deactivate_wp_avorg_multisite_catalog' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-avorg-multisite-catalog.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_avorg_multisite_catalog() {

	$plugin = new Wp_Avorg_Multisite_Catalog();
	$plugin->run();

}
run_wp_avorg_multisite_catalog();
