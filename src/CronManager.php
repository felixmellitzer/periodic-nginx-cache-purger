<?php
namespace PNCP;

class CronManager {

	public static function schedule($timestamp, $recurrence, $hook = 'rt_nginx_helper_purge_all', $args = array())
	{
		return \wp_schedule_event($timestamp, $recurrence, $hook, $args);
	}

	public static function clear($hook = 'rt_nginx_helper_purge_all')
	{
		return \wp_clear_scheduled_hook($hook);
	}

	public static function isScheduled($hook = 'rt_nginx_helper_purge_all')
	{
		return \wp_next_scheduled($hook);
	}
}