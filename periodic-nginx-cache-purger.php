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
 * Version:           1.1.0
 * Author:            TFM Agency GmbH
 * Author URI:        https://tfm.agency
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Can't open file directly
if (!defined('ABSPATH')) {
    die();
}

require_once __DIR__ . '/vendor/autoload.php';


register_activation_hook(__FILE__, 'PNCP\Activator::activate');
register_deactivation_hook(__FILE__, 'PNCP\Deactivator::deactivate');
register_uninstall_hook(__FILE__, 'PNCP\Uninstaller::uninstall');


function pncpRunPlugin()
{
    $plugin_checker = new PNCP\PluginChecker();
    $plugin_checker->run();

    $plugin = new PNCP\Main;
    $plugin->run();
}

pncpRunPlugin();
