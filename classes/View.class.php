<?php
/**
 * View class.
 * 
 * @extends Smarty
 */
class View extends Smarty
{
	public function __construct()
	{
		// Call Smarty's contructor
		parent::__construct();
		
		// Set Smarty's directories
		$this->template_dir = ABSPATH.'html';
		$this->compile_dir = ABSPATH.'html/compile';
		$this->config_dir = ABSPATH.'html/config';
		$this->cache_dir = ABSPATH.'html/cache';
		
		// Shut up PHP's warning about the timezone
		date_default_timezone_set('Europe/Amsterdam');
		
		// Set up some general info
		$general_info = array(
			'name' => SITE_NAME,
			'url' => SITE_URL
		);
		
		// Assign the general/default info to the View object
		$this->assign('general', $general_info);
		
		$this->registerPlugin('block', 't', 'gettext');
	}
}