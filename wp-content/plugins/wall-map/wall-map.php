<?php
/*
Plugin Name: Wall Maps
Plugin URI: http://wordpress.org/extend/plugins/wordpress-importer/
Description: Custom Built Walls Map
Author: Union
Author URI: https://github.com/unionco
Version: 1.0.0
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function install()
{
    require_once plugin_dir_path(__FILE__) . 'includes/Installer.php';
    Installer::install_plugin();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function uninstall()
{
    require_once plugin_dir_path(__FILE__) . 'includes/Installer.php';
    Installer::uninstall_plugin();
}

register_activation_hook(__FILE__, 'install');
register_deactivation_hook(__FILE__, 'uninstall');

/**
 * The core plugin class that is used to define
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/WallMapPlugin.php';

function run_wall_map()
{
    $plugin = new WallMapPlugin();
    $plugin->run();
}

run_wall_map();
