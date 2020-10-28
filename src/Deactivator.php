<?php
namespace PNCP;

class Deactivator {

	public static function deactivate()
	{
		CronManager::clear();
		Options::unregisterSettings();
	}
}