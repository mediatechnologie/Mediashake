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
	
	protected $user;
	
	/**
	 * page
	 * the page type that should be served
	 * 
	 * @var string
	 * @access protected
	 */
	protected $page;
	
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
		$this->view = new MView;
		$this->wf = new WorkFactory;
		$this->user = new User;
		
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
		$navigation = array(
			'showcase'		=>	_( 'Showcase' ),
			'people'		=>	_( 'People' ),
		);
		
		// Add navigation items if the user is logged in
		if(!empty($_SESSION['user']))
		{
			// Navigation if a user is logged in
			$navigation = array_merge($navigation, array(
			//	'groups'		=>	_( 'Groups' ),
			//	'converse'		=>	_( 'Converse' ),
			//	'collaborate'	=>	_( 'Collaborate' ),
			//	'settings'		=>	_( 'Settings' ),
				
				'upload'		=>	_( 'Upload' ),
				'profile/'		=>	_( 'Profile' ),
				'action/logout'	=>	_( 'Log out' )
			));
		}
		// Or when noboby is logged in
		else
		{
			$navigation = array_merge($navigation, array(
				'landing'		=>	_( 'Sign up' )
			));
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
		
		return $statement->fetchAll(PDO::FETCH_ASSOC);
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
				$wk = $this->wf->fetch(array(
					'id' => $this->path[1],
					'output_type' => 'array'
				));

				// Comments
				$comments = $this->wf->fetchComments($this->path[1]);
				$this->view->assign('comments', $comments);
				
				// Work
				$this->view->setTitle($wk['title']);
				$this->view->assign('work', $wk);
				break;
			}
			case 'landing':
			case 'showcase':
			{
				$wk = $this->wf->fetchAll();
				$this->view->assign('work', $wk);
				break;
			}
			case 'people':
			{
				$uf = new UserFactory;
				$this->view->assign('users', $uf->fetchAll());
				break;
			}
			case 'user':
			{
				$uf = new UserFactory;
				if(!isset($this->path[1]))
					throw new Exception(404);
				$this->view->assign('profile', $uf->fetch(array('username' => $this->path[1])));
				break;
			}
			case 'explore':
			{
				$popular_work = $this->wf->fetchAll(array(
					'sort_by' => 'views',
					'limit' => 15
				));
				
				$this->view->assign('popular', $popular_work);
				$this->view->assign('schools', $this->getSchools());
				break;
			}
			case 'home':
			{
				$recent_work = $this->wf->fetchAll(array(
					'sort_by' => 'date',
					'limit' => 5
				));
				$popular_work = $this->wf->fetchAll(array(
					'sort_by' => 'views',
					'limit' => 10
				));
				
				$this->view->assign('recent', $recent_work);
				$this->view->assign('popular', $popular_work);
				$this->view->assign('schools', $this->getSchools());
				break;
			}
		}
	}
	
	/**
	 * determinePage function.
	 * Analyze the request URI and determine which page to get.
	 * @access private
	 * @return string
	 */
	private function determinePage()
	{
		// Get path and save it as array
		$path = str_replace('/mediashake/', '', $_SERVER['REQUEST_URI']);
		$path = explode('/', $path);
		$path = array_filter($path);
		$path = array_values($path);
		
		// Write path to a property for later use in class
		$this->path = $path;
		
		// Check if an action should be performed first
		if(!empty($path[0]) and $path[0] == 'action')
		{
			$this->performAction($path[1]);
			$page = 'action';
		}
		// Send new visitors to the landing page
		elseif(empty($path[0]) and empty($_SESSION['user']))
		{
			$page = 'landing';
		}
		// But send logged in visitors to the showcase
		elseif(empty($path[0]) and is_array($_SESSION['user']))
		{
			$page = 'showcase';
		}
		else
		{
			$page = $path[0];
		}
		
		// Make path a class property for later use
		$this->path = $path;
		
		$this->page = $page;
		return $page;
	}
	
	/**
	 * performAction function.
	 * 
	 * @access private
	 * @param mixed $action
	 * @return void
	 */
	private function performAction($action)
	{
		switch($action)
		{
			case 'comment':
			{
				$this->wf->addComment( $this->path[2], $_POST['comment'] );
				break;
			}
			case 'login':
			{
				if( $this->user->login($_POST['username'], $_POST['password']) )
					return true;
				else
					return false;
				
				break;
			}
			case 'logout':
			{
				if( $this->user->logout() )
				{
					$this->user = new User;
					return true;
				}
				else
					return false;
				
				break;
			}
			case 'upload':
			{
				$this->uploadWork();
				$this->page = 'showcase';
				break;
			}
			case 'register':
			{
				
				break;
			}
		}
	}
	
	/**
	 * uploadWork function.
	 * Upload work from $_POST and $_FILES
	 * @access private
	 * @return bool
	 */
	private function uploadWork()
	{
		// Get ourselves a blank Work object.
		$w = $this->wf->create();
		$type = (isset($_POST['type'])) ? $_POST['type'] : null;
		
		// Se what type of work we're dealing with.
		switch($type)
		{
			case 'website':
			{
				$w->type = 1;
				$w->addWebsite($_POST['website_url']);
				break;
			}
			case 'video':
			{
				$w->type = 2;
				$w->addVideo($_POST['video_url']);
				break;
			}
			case 'document':
			{
				$w->type = 3;
				// Continue...
			}
			case 'image':
			{
				if(isset($_FILES['image_file']))
					$file = $_FILES['image_file'];
				elseif(isset($_FILES['document_file']))
					$file = $_FILES['document_file'];
				else
					throw new Exception('No file received.');
				
				$w->addFile($file);
				break;
			}
			default:
			{
				throw new Exception('Invalid upload type.');
			}
		}
		$w->title = Database::sanitize($_POST['title']);
		$w->description = Database::sanitize($_POST['description']);
		$w->owner = $_SESSION['user']['id'];
		$w->school = $school = $_SESSION['user']['school'];
		
		if($this->wf->add($w))
			return true;
		else
			return false;
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
			$this->view->setTitle(ucfirst($this->page));
			
			$file = 'html/pages/'.$this->page.'.html';
			
			// Check if the template file exists,
			// If it doesn't, throw a 404 exception
			if(!file_exists($file))
			{
				throw new Exception(404);
			}
			
			// See what page content we should get
			// and assign it.
			$this->assignContent();
		}
		catch(Exception $e)
		{
			if($e->getMessage() == 404)
			{
				header('HTTP/1.1 404 Not Found');
				$file = 'html/pages/404.html';
				$this->view->setTitle(_('Page not found'));
			}
			elseif($e->getMessage() == 403)
			{
				header('HTTP/1.1 403 Forbidden');
				$file = 'html/pages/403.html';
				$this->view->setTitle(_('Forbidden'));
			}
			elseif($e->getMessage() == 500)
			{
				header('HTTP/1.1 500 Forbidden');
				$file = 'html/pages/500.html';
				$this->view->setTitle(_('Internal server error'));
			}
			else
			{
				header('HTTP/1.1 500 Internal Server Error');
				$file = 'html/pages/error.html';
				$this->view->setTitle(_('Error'));
				$page['content'] = $e->getMessage();
				$this->view->assign('content', $e->getMessage());
			}
		}
		
		// Assign the navigation to the view
		$this->view->assign('nav', $this->getNavigation());
		
		// Assign page info/contents to the view
		$page['title'] = $this->view->getTitle();

		$this->view->assign('page', $page);
		
		// Assign user data to the view
		if(array_key_exists('user', $_SESSION))
			$this->view->assign('user', $_SESSION['user']);
		else
			$this->view->assign('user', array());
		
		
		// Output
		try
		{
			$this->view->setFile($file);
			$output = $this->view->fetch();
		}
		catch(SmartyException $e)
		{
			$output = _( 'Something is wrong with the template!' ) .' '.
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
