<?php
/**
 * Site class.
 */
class Site
{
	/**
	 * db
	 * the database object
	 * @var mixed
	 * @access protected
	 */
	protected $db;
	/**
	 * page
	 * the page type that should be served
	 * (default value: 'home')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $page = 'home';
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Set up the database 
		$this->db = new Database;
		
		// Determine which page should be served
		if(!empty($_GET['p']))
		{
			$page = $_GET['p'];
			$this->page = $page;
		}
		
		// Check if an action should be performed
		switch($_GET['action'])
		{
			case 'logout':
				session_destroy();
				header('location: index.php');
				break;
			
			case 'upload':
				$Work = new Work;
				$Work->upload();
				break;
			
			case 'newvideo':
				$Work = new Work;
				$Work->newVideo();
				break;
			
			case 'newwebsite':
				$Work = new Work;
				$Work->newWebsite();
				break;
		}
	}
	
	/**
	 * navigation function.
	 * 
	 * @access protected
	 * @param mixed $ul (default: null)
	 * @return void
	 */
	protected function navigation($ul = false)
	{
		if($_SESSION['user'])
		{
			// Navigation if a user is logged in
			$navigation = array(
				
				'home'		=>	'Home',
				// 'profile'	=>	'Profile', ///
				'explore'	=>	'Explore',
				// 'messages'	=>	'Messages', ///
				'upload'	=>	'Upload',
				'account'	=>	'Account'
			);
		}
		else
		{
			// Navigation if a user isn't logged in
			$navigation = array(
				
				'home'		=>	'Home',
				'explore'	=>	'Explore',
				'register'	=>	'Register',
				'login'		=>	'Login'
				
			);
		}
		
		// Check if we should return the navigation as a <ul>...
		if($ul == true)
		{
			foreach($navigation as $link => $name)
			{
				$ul_navigation = $ul_navigation.'<li><a href="?p='.$link.'" id="'.$link.'">'.$name.'</a></li>'."\n";
			}
			
			return '<ul id="navigation">'.$ul_navigation.'</ul>';
		}
		// ...Or return it as an array
		else
		{
			return $navigation;
		}
	}
	
	/**
	 * invoke function.
	 * 
	 * Get the necessary data
	 * and send it to the output buffer
	 * 
	 * @access public
	 * @return void
	 */
	public function invoke()
	{
		try
		{
			// Head
			$title = ucfirst($this->page);
			$navigation = $this->navigation($ul = true);
			
			// Body
			$file = 'html/pages/'.$this->page.'.html';
			
			// Check if the template file exists,
			// If it doesn't, throw a 404 exception
			if(!file_exists($file))
			{
				throw new SiteException(404);
			}
		}
		catch(SiteException $e)
		{
			if($e->getMessage() == 404)
			{
				$file = 'html/pages/404.html';
				$title = 'Page not found.';
			}
		}
		
		// Get the header
		require('html/head.html');
		
		require($file);
		
		// Get the footer
		require('html/foot.html');
	}

}

/**
 * SiteException class.
 * 
 * @extends Exception
 */
class SiteException extends Exception
{

}
