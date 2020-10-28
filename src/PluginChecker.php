<?php
namespace PNCP;

class PluginChecker {
	public function __construct()
	{
		if (!function_exists('is_plugin_active')) {
	        require_once(ABSPATH . '/wp-admin/includes/plugin.php');
	    }
	}

	public function isHelperPluginActive()
	{
		return is_plugin_active('nginx-helper/nginx-helper.php');
	}

	public function deactivateThisPlugin()
	{
		deactivate_plugins(PNCP_PLUGIN_BASENAME);
	}

	public function run()
	{
		if (!$this->isHelperPluginActive()) {
	        // If Nginx Helper Plugin is not active -> error notice and deactivate plugin
	        add_action('admin_notices', array(__CLASS__, 'notActiveErrorNotice'));
	        $this->deactivateThisPlugin();
	    }
	}

	public static function notActiveErrorNotice()
	{
	    ?>
	    <div class="notice notice-error">
	        <p>Nginx Helper Plugin is REQUIRED!</p>
	    </div>
	    <?php
	}
}