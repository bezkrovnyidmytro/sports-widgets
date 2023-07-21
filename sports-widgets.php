<?php
/**
 * The plugin bootstrap file
 * @package           sports-widgets
 *
 * @wordpress-plugin
 * Plugin Name:       Sports Widgets
 * Description:       This widget will display the latest Sports fixtures for that day's games.
 * Version:           1.6.2
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sports-widgets
 * Domain Path:       /languages
 * Requires at least: 5.9
 * Requires PHP:      7.4
 */

use DmytroBezkrovnyi\SportsWidgets\PluginActivator;
use DmytroBezkrovnyi\SportsWidgets\PluginDeactivator;
use DmytroBezkrovnyi\SportsWidgets\PluginMain;
use DmytroBezkrovnyi\SportsWidgets\Registry\EndpointsRegistry;

/**
 * Run composer autoload
 */
$composer_path = __DIR__ . '/vendor/autoload.php';

if (file_exists($composer_path)) {
    require_once($composer_path);
}
else {
    exit('Vendor autoload is not found. Please run "composer install" under plugin root directory: "' . __DIR__ . '".');
}

defined('VCSW_PLUGIN_NAME') || define('VCSW_PLUGIN_NAME', 'sports-widgets');

defined('VCSW_DOMAIN') || define('VCSW_DOMAIN', 'sports-widgets');

defined('VCSW_DIR_PATH') || define('VCSW_DIR_PATH', plugin_dir_path(__FILE__));

defined('VCSW_URL_PATH') || define('VCSW_URL_PATH', plugin_dir_url(__FILE__));

defined('VCSW_FRONTEND_URL_PATH') || define('VCSW_FRONTEND_URL_PATH', VCSW_URL_PATH . 'frontend/');

defined('VCSW_FRONTEND_DIR_PATH') || define('VCSW_FRONTEND_DIR_PATH', VCSW_DIR_PATH . 'frontend/');

defined('VCSW_FRONTEND_BUILD_URL_PATH') || define('VCSW_FRONTEND_BUILD_URL_PATH', VCSW_FRONTEND_URL_PATH . 'build/');

defined('VCSW_FRONTEND_BUILD_DIR_PATH') || define('VCSW_FRONTEND_BUILD_DIR_PATH', VCSW_FRONTEND_DIR_PATH . 'build/');

/**
 * Register activation hook.
 */
if (! function_exists('vcsw_on_activation')) {
    /**
     * @return void
     */
    function vcsw_on_activation() {
        PluginActivator::activate();
    }
    
    register_activation_hook(__FILE__, 'vcsw_on_activation');
}

/**
 * Register deactivation hook.
 */
if (! function_exists('vcsw_on_deactivation')) {
    function vcsw_on_deactivation() {
        PluginDeactivator::deactivate();
    }
    
    register_deactivation_hook(__FILE__, 'vcsw_on_deactivation');
}

/**
 * Begins execution of the plugin.
 */
if (! function_exists('vcsw_run_plugin')) {
    function vcsw_run_plugin() {
        global $vcsw_plugin;
        
        if (! isset($vcsw_plugin)) {
            $vcsw_plugin = new PluginMain();
            $vcsw_plugin->run();
        }
        
        return $vcsw_plugin;
    }
    
    vcsw_run_plugin();
}

/**
 * REST API Init
 */
if (! function_exists('vcsw_rest_api_init')) {
    function vcsw_rest_api_init() {
        add_action('rest_api_init', [(new EndpointsRegistry()), 'registerAllEndpoints']);
    }
    
    vcsw_rest_api_init();
}
