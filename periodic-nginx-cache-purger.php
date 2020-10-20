<?php
/**
 * Daily Purger for Nginx Helper
 *
 * @package           PluginPackage
 * @author            Felix Mellitzer
 * @copyright         2020 TFM Agency GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Daily Purger for Nginx Helper
 * Description:       purges cache periodically with Nginx Helper WordPress Plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Felix Mellitzer
 * Author URI:        https://tfm.agency
 * Text Domain:       periodic-nginx-cache-purger
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Can't open file directly
if (!defined('ABSPATH')) {
	die();
}

if (!function_exists('is_plugin_active')) {
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

function errorNoticeIfPluginIsNotActive()
{
	?>
    <div class="notice notice-error">
    	<p>Nginx Helper Plugin is REQUIRED!</p>
    </div>
    <?php
}

function addDailyPurgeCronEvent()
{
    if (!wp_next_scheduled('rt_nginx_helper_purge_all')) {
    	wp_schedule_event(time(), 'daily', 'rt_nginx_helper_purge_all'); //Hook from the Nginx Helper Plugin
    } 
}

// Checks if Nginx Helper Plugin is active
if (is_plugin_active('nginx-helper/nginx-helper.php')) {
	register_activation_hook(__FILE__, 'addDailyPurgeCronEvent');
	
} else { // If Nginx Helper Plugin is not active -> error notice and deactivate plugin
	add_action('admin_notices', 'errorNoticeIfPluginIsNotActive');
	deactivate_plugins('periodic-nginx-cache-purger/periodic-nginx-cache-purger.php');
}
