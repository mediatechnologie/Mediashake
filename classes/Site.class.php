<?php
/**
 * Site class.
 */
class Site
{
	/**
	 * db
	 * Database object
	 * @var mixed
	 * @access protected
	 */
	protected $db;
	
	/**
	 * view
	 * View object
	 * @var mixed
	 * @access protected
	 */
	protected $view;
	
	/**
	 * wf
	 * WorkFactory object
	 * @var mixed
	 * @access protected
	 */
	protected $wf;
	
	/**
	 * page
	 * the page type that should be served
	 * (default value: 'home')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $page = 'landing';
	
	/**
	 * action
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $action = '';
	
	protected $file = '';
	protected $path = array();
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Set up some objects
		$this->db = new Database;
		$this->view = new View;
		$this->wf = new WorkFactory;
		
		// Determine which page should be served
		$this->determinePage();
	}
	
	/**
	 * getNavigation function.
	 * return navigation items as an assoc. array
	 * @access protected
	 * @return array
	 */
	protected function getNavigation()
	{
		$navigation = array();
		
		if(!empty($_SESSION['user']))
		{
			// Navigation if a user is logged in
			$navigation = array(
				'showcase'	=>	'Showcase',
				'people'			=>	'People',
				#'groups'			=>	'Groups',
				#'converse'	=>	'Converse',
				#'collaborate'	=>	'Collaborate',
				#'settings'	=>	'Settings',
				'upload'			=>	'Upload',
				'action/logout'			=>	'Logout'
			);
		}
		else
		{
		}
		
		return $navigation;
	}
	
	/**
	 * getSchools function.
	 * return the schools as an assoc. array
	 * @access protected
	 * @return array
	 */
	protected function getSchools()
	{
		$sql = 'SELECT * FROM schools ORDER BY name';
		$statement = $this->db->query($sql);
		
		$schools = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return $schools;
	}
	
	/**
	 * assignContent function.
	 * select page content to get
	 * and assign to the view object
	 * @access protected
	 * @return void
	 */
	protected function assignContent()
	{
		switch($this->page)
		{
			case 'work':
			{
				$wk = $this->wf->fetch(
					array('id' => $_GET['id'],'output_type' => 'array')
				);
				$this->view->assign('work', $wk);
				break;
			}
			case 'explore':
			{
				$popular_work = $this->wf->fetchAll(array('sort_by' => 'views', 'limit' => 15));
				
				$this->view->assign('popular', $popular_work);
				$this->view->assign('schools', $this->getSchools());
				break;
			}
			case 'home':
			{
				$recent_work = $this->wf->fetchAll(array('sort_by' => 'date', 'limit' => 5));
				$popular_work = $this->wf->fetchAll(array('sort_by' => 'views', 'limit' => 10));
				
				$this->view->assign('recent', $recent_work);
				$this->view->assign('popular', $popular_work);
				$this->view->assign('schools', $this->getSchools());
				
				break;
			}
		}
	}
	
	private function determinePage()
	{
		// Get path and save it as array
		$path = str_replace('/mediashake/', '', $_SERVER['REQUEST_URI']);
		$path = explode('/', $path);
		$path = array_filter($path);
		$path = array_values($path);
		
		if(empty($path[0]))
			$path[0] = '';
		
		// Check if an action should be performed first
		if($path[0] == 'action')
		{
			$this->performAction($path[1]);
		}
		elseif(empty($_SESSION['user']))
		{
			// User isn't logged in, go to landing page
			$this->page = 'landing';
		}
		else
		{
			// User is logged in, see what page should be served
			switch($path[0])
			{
				case '':
					$this->page = 'showcase';
					break;
				case 'showcase':
					$this->page = 'showcase';
					break;
				case 'people':
					$this->page = 'people';
					break;
				case 'user':
					$this->page = 'profile';
					break;
				case 'converse':
					$this->page = 'converse';
					break;
				case 'settings':
					$this->page = 'settings';
					break;
				case 'upload':
					$this->page = 'upload';
					break;
				case 'work':
					$this->page = 'work';
					break;
			}
			
			// If no page has been set, the page wasn't allowed, so show 404 error
			if(!isset($this->page))
				$this->page = '_404';
				
			// Make path a class property for later use
			$this->path = $path;
		}
	}
	
	private function performAction($action)
	{
		switch($action)
		{
			case 'login':
				$user = new User;
				if( $user->login($_POST['username'], $_POST['password']) )
					return true;
				else
					return false;
				break;
			case 'logout':
				$user = new User;
				if( $user->logout() )
					return true;
				else
					return false;
				break;
		}
	}
	
	/**
	 * invoke function.
	 * 
	 * return the html output of the system
	 * 
	 * @access public
	 * @return void
	 */
	public function invoke()
	{
		// Set the array for page info and contents
		$page = array();
		
		try
		{
			// Head
			$page['title'] = ucfirst($this->page);
			
			// Body
			$file = 'html/pages/'.$this->page.'.html';
			
			// See what page content we should get
			// and assign it.
			$this->assignContent();
			
			// Check if the template file exists,
			// If it doesn't, throw a 404 exception
			if(!file_exists($file))
			{
				throw new Exception(404);
			}
		}
		catch(Exception $e)
		{
			if($e->getMessage() == 404)
			{
				header('HTTP/1.1 404 Not Found');
				$file = 'html/pages/404.html';
				$page['title'] = 'Page not found';
			}
			else
			{
				$file = 'html/pages/error.html';
				$page['title'] = 'Error';
				$page['content'] = $e->getMessage();
			}
		}
		
		// Assign the navigation to the view
		$this->view->assign('nav', $this->getNavigation());
		
		// Assign page info/contents to the view
		$this->view->assign('page', $page);
		
		// Assign user data to the view
		if(array_key_exists('user', $_SESSION))
			$this->view->assign('user', $_SESSION['user']);
		else
			$this->view->assign('user', array());
		
		
		// Output
		try
		{
			$output = $this->view->fetch($file);
		}
		catch(SmartyException $e)
		{
			$output = 'Something is wrong with the template! '.
				$e->getMessage();
		}
		
		return $output;
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
