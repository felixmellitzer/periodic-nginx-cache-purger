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
    if (isset($_POST['purge_options'])) {
        update_option('purge_options', $_POST['purge_options']);
    }

    $option = get_option('purge_options');

    if ($option == 'hourly') {
        wp_clear_scheduled_hook('rt_nginx_helper_purge_all');
        wp_schedule_event(time(), 'hourly', 'rt_nginx_helper_purge_all');
    } elseif ($option == 'twicedaily') {
        wp_clear_scheduled_hook('rt_nginx_helper_purge_all');
        wp_schedule_event(time(), 'twicedaily', 'rt_nginx_helper_purge_all');
    } elseif ($option == 'daily') {
        wp_clear_scheduled_hook('rt_nginx_helper_purge_all');
        wp_schedule_event(time(), 'daily', 'rt_nginx_helper_purge_all');
    } elseif ($option == 'weekly') {
        wp_clear_scheduled_hook('rt_nginx_helper_purge_all');
        wp_schedule_event(time(), 'weekly', 'rt_nginx_helper_purge_all');
    }
}

function pncpSetDefaultInterval()
{
    update_option('purge_options', 'weekly');
    pncpAddPeriodicPurgeCronEvent();
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
       register_activation_hook(__FILE__, 'pncpSetDefaultInterval');
        
    } else { // If Nginx Helper Plugin is not active -> error notice and deactivate plugin
        add_action('admin_notices', 'pncpErrorNoticeIfPluginIsNotActive');
        deactivate_plugins('periodic-nginx-cache-purger/periodic-nginx-cache-purger.php');
    }
}

pncpAddPeriodicPurgeCronEvent();

pncpRunPeriodicNginxCachePurger();

add_action('admin_init', 'pncpSetPeriodicSettings');

# Register all Settings and Fields.
function pncpSetPeriodicSettings()
{
    register_setting('purge_options', 'purge_options');

    add_settings_section('main-section', 'Periodic Nginx Cache Purger', 'pncpSettingsTitle', 'general');

    add_settings_field('interval', 'Choose your Purge Interval', 'pncpAddIntervalForm', 'general', 'main-section');
}

function pncpSettingsTitle()
{ 
    echo '';
}

function pncpAddIntervalForm()
{ ?><form method="post" action="options.php">
        <select name="purge_options">
            <option value="hourly" <?php selected(get_option('purge_options'), "hourly"); ?>>Hourly</option>
            <option value="twicedaily" <?php selected(get_option('purge_options'), "twicedaily"); ?>>Twicedaily</option>
            <option value="daily" <?php selected(get_option('purge_options'), "daily"); ?>>Daily</option>
            <option value="weekly" <?php selected(get_option('purge_options'), "weekly"); ?>>Weekly</option>
        </select>
    </form> <?php
}
