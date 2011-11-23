<?php
/**
 * MView class.
 * Mediashake View class, with some custom stuff.
 * @extends View
 */
class MView extends View
{
	private $title = '';
	
	public function __construct()
	{
		// Set up some general info
		$general_info = array(
			'name' => SITE_NAME,
			'url' => SITE_URL,
			'charset' => CHARSET
		);
		
		// Assign the general/default info to the View object
		$this->assign('general', $general_info);
		
		set_include_path('html/'.PATH_SEPARATOR.get_include_path());
	}
	
	public function setTitle($t)
	{
		if(is_string($t))
		{
			$this->title = $t;
		}
		else
		{
			return false;
		}
	}
	
	public function getTitle()
	{
		return $this->title;
	}
}