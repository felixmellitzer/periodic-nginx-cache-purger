<?php
namespace PNCP;

class Activator {

	public static function activate()
	{
		$purge_option = Options::getPurgeOption();

		if ($purge_option === false) {
			Options::setPurgeOption('weekly');
		} else {
			CronManager::schedule(
				time(),
				$purge_option
			);
		}

		Options::registerSettings();
	}
}