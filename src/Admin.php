<?php
namespace PNCP;

class Admin {
	# Register all Settings and Fields.
	public function setPeriodicSettings()
	{
	    \add_settings_section('pncp-purge-section', 'Periodic Nginx Cache Purger', function() { echo ''; }, 'general');
	    \add_settings_field('pncp-purge-option', 'Choose your Purge Interval', 'PNCP\Admin::addIntervalForm', 'general', 'pncp-purge-section');
	}

	public static function addIntervalForm()
	{
		?>
	        <select name="PNCP_purge_option">
	        	<?php 
	        	foreach(['hourly', 'twicedaily', 'daily', 'weekly'] as $value) {
	        		echo "<option value=\"" . $value . "\" " . selected(Options::getPurgeOption(), $value, $echo = false) . ">" . ucfirst($value) . "</option>";
	        	}
	        	?>
	        </select>
	 	<?php
	}

	public function registerSettings() {
		$this->setPeriodicSettings();
	}

	public function updatePurgeOption() {
		if (\current_user_can('manage_options') && $_POST['PNCP_purge_option']) {
			Options::setPurgeOption($_POST['PNCP_purge_option']);
		}
	}
}