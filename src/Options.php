<?php
namespace PNCP;

class Options {

	private static $saved_options = array();

	public static function getPurgeOption()
	{
		return self::get('PNCP_purge_option');
	}

	public static function setPurgeOption($value)
	{
		$success = self::update('PNCP_purge_option', $value);
		
		if ($success) {
			if (CronManager::isScheduled()) {
				CronManager::clear();
			}

			CronManager::schedule(time(), $value);
		}

		return $success;
	}

	public static function deletePurgeOption()
	{
		return self::delete('PNCP_purge_option');
	}

	public static function registerSettings() {
		\register_setting('PNCP_purge_option', 'PNCP_purge_option');
	}

	public static function unregisterSettings() {
		\unregister_setting('PNCP_purge_option', 'PNCP_purge_option');
	}

	private static function get($option, $default = false)
	{
		if (isset(self::$saved_options[$option])) {
			return self::$saved_options[$option];
		}

		return self::$saved_options[$option] = \get_option($option, $default);
	}

	private static function update($option, $value, $autoload = null)
	{
		$success = \update_option($option, $value, $autoload);

		if ($success) {
			self::$saved_options[$option] = $value;
		}

		return $success;
	}

	private static function delete($option)
	{
		$success = \delete_option($option);

		if ($success) {
			unset(self::$saved_options[$option]);
		}

		return $success;
	}
}