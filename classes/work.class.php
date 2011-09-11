<?php
/**
 * Work class.
 * 
 * @extends Page
 */
class Work extends Page
{
	public $type;
	
	public $owner;
	public $school = 0;
	
	public $filename;
	public $description = '';
	
	public $rating;
	public $votes;
	public $views;
	public $date;
	
	public function __construct($id)
	{
		// Call the parent constructor
		parent::__construct();
		
		// Decode the title
		$this->title = urldecode($this->title);
		
		
		if(is_numeric($id))
		{
			$this->id = $id;
		}
		else
		{
			$id = 0;
		}
	}
	
	
}