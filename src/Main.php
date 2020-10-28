<?php
namespace PNCP;

class Main {

	public function run()
	{
		$this->defineAdminHooks();
	}

	public function defineAdminHooks()
	{
		$admin = new Admin;
		\add_action('admin_init', array($admin, 'updatePurgeOption'));
		\add_action('admin_init', array($admin, 'registerSettings'));
	}
}