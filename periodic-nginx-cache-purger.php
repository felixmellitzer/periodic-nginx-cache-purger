<?php
/**
 * Periodic Nginx Cache Purger
 *
 * @package           PeriodicNginxCachePruger
 * @author            TFM Agency GmbH
 * @copyright         2020 TFM Agency GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Periodic Nginx Cache Purger
 * Plugin URI:        https://github.com/TFM-Agency/periodic-nginx-cache-purger
 * Description:       Purges cache periodically with Nginx Helper WordPress Plugin.
 * Version:           1.0.0
 * Author:            TFM Agency GmbH
 * Author URI:        https://tfm.agency
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Can't open file directly
if (!defined('ABSPATH')) {
    die();
}

function pncpErrorNoticeIfPluginIsNotActive()
{
    ?>
    <div class="notice notice-error">
        <p>Nginx Helper Plugin is REQUIRED!</p>
    </div>
    <?php
}

function pncpAddPeriodicPurgeCronEvent()
{
    if (!wp_next_scheduled('rt_nginx_helper_purge_all')) {
        wp_schedule_event(time(), 'twicedaily', 'rt_nginx_helper_purge_all'); //Hook from the Nginx Helper Plugin
    } 
}

function pncpClearCronJobOnDeactivation()
{
    wp_clear_scheduled_hook('rt_nginx_helper_purge_all');
}

function pncpRunPeriodicNginxCachePurger()
{
    register_deactivation_hook(__FILE__, 'pncpClearCronJobOnDeactivation');

    if (!function_exists('is_plugin_active')) {
        require_once(ABSPATH . '/wp-admin/includes/plugin.php');
    }

    // Checks if Nginx Helper Plugin is active
    if (is_plugin_active('nginx-helper/nginx-helper.php')) {
       register_activation_hook(__FILE__, 'pncpAddPeriodicPurgeCronEvent');
        
    } else { // If Nginx Helper Plugin is not active -> error notice and deactivate plugin
        add_action('admin_notices', 'pncpErrorNoticeIfPluginIsNotActive');
        deactivate_plugins('periodic-nginx-cache-purger/periodic-nginx-cache-purger.php');
    }
}
pncpRunPeriodicNginxCachePurger();
