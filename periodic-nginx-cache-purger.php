<?php
/**
 * Periodic Nginx Cache Purger
 *
 * @package           PluginPackage
 * @author            Felix Mellitzer
 * @copyright         2020 TFM Agency GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Periodic Nginx Cache Purger
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

function addDailyPurgeCronEvent()
{
    if (!wp_next_scheduled('rt_nginx_helper_purge_all')) {
        wp_schedule_event(time(), 'daily', 'rt_nginx_helper_purge_all'); //Hook from the Nginx Helper Plugin
    }
}

register_activation_hook(__FILE__, 'addDailyCronEvent');
