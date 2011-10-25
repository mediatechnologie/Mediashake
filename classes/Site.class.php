<?php

	class Site
	{
		
		// This contains the database connection
		protected $db;
		
		// This contains the name of the template file
		protected $file;
		
		// This contains an array of the path
		protected $path;
		
		public function __construct()
		{
			$this->db = new Database;
			$this->determinePage();
		}
		
		private function determinePage()
		{
			// Get path and save it as array
			$path = str_replace('/ecmm/', '', $_SERVER['REQUEST_URI']);
			$path = explode('/',$path);
				
			// Check if an action should be performed first
			if($path[0] == 'action')
			{
				$this->performAction($path[1]);
			}
			elseif($_SESSION['user'] == '')
			{
				// User isn't logged in, go to landing page
				$this->file = 'landing';
			}
			else
			{
				// User is logged in, see what page should be served
				switch($path[0])
				{
					case '':
						$this->file = 'showcase';
						break;
					case 'showcase':
						$this->file = 'showcase';
						break;
					case 'people':
						$this->file = 'people';
						break;
					case 'user':
						$this->file = 'profile';
						break;
					case 'converse':
						$this->file = 'converse';
						break;
					case 'settings':
						$this->file = 'settings';
						break;
					case 'upload':
						$this->file = 'upload';
						break;
				}
				
				// If no page has been set, the page wasn't allowed, so show 404 error
				if(!isset($this->file))
					$this->file = '_404';
					
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
					$user->login($_POST['username'], $_POST['password']);
					break;
				case 'logout':
					$user = new User;
					$user->logout();
					break;
			}
		}
		
		private function createNavigation()
		{
			// The navigation items
			$navigation = array(
				'showcase'	=>	'Showcase',
				'people'			=>	'People',
				#'groups'			=>	'Groups',
				'converse'	=>	'Converse',
				#'collaborate'	=>	'Collaborate',
				#'settings'	=>	'Settings',
				'upload'			=>	'Upload',
			);
			
			// Create the HTML code
			foreach($navigation as $url => $title)
			{
				if($this->file == $url)
				{
					$html .= '<li class="active"><a href="'.$url.'">'.$title.'</a></li>';
				}
				else
				{
					$html .= '<li><a href="'.$url.'">'.$title.'</a></li>';
				}
			}
			
			// Return it inside an ordered list
			return '<ol id="top-navigation">'.$html.'</ol>';
		}
		
		private function prepareData()
		{
			switch($this->file)
			{
				case 'profile':
					$user = new User;
					
					$data = array(
						'current_user' 	=> $_SESSION['user'],
						'user'										=>	$user->getUserByUsername($this->path[1])
					);
					break;
			}
			
			if($data === null)
				return array();
			else
				return $data;
		}
		
		public function invoke()
		{
			if($_SESSION['user'] == '')
			{
				require('html/landing.html');
			}
			else
			{
				// Buffer data
				$navigation = $this->createNavigation();
				extract($this->prepareData());
				
				// Head
				require('html/_head.html');
				
				// Body
				if(file_exists('html/'.$this->file.'.html'))
					require('html/'.$this->file.'.html');
				else
					require('html/_404.html');
				
				// Foot
				require('html/_foot.html');
			}
		}
		
	}

?>