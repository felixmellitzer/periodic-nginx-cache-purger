<?php
namespace PNCP;

class Uninstaller {

	public static function uninstall()
	{
		 Options::deletePurgeOption();
	}
}
