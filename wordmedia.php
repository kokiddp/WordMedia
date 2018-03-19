<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.elk-lab.com/
 * @since             0.0.1
 * @package           Wordmedia
 *
 * @wordpress-plugin
 * Plugin Name:       WordMedia
 * Plugin URI:        https://github.com/kokiddp/WordMedia
 * Description:       WordPress VisaMultimedia integration.
 * Version:           0.0.2
 * Author:            Gabriele Coquillard
 * Author URI:        http://www.elk-lab.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordmedia
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WORDMEDIA_VERSION', '0.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordmedia-activator.php
 */
function activate_wordmedia() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordmedia-activator.php';
	Wordmedia_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordmedia-deactivator.php
 */
function deactivate_wordmedia() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordmedia-deactivator.php';
	Wordmedia_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wordmedia' );
register_deactivation_hook( __FILE__, 'deactivate_wordmedia' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wordmedia.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_wordmedia() {

	$plugin = new Wordmedia();
	$plugin->run();

}
run_wordmedia();
