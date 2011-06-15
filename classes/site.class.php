<?php

	class Site extends Database
	{
		
		protected $page;
		
		public function __construct()
		{
			
			parent::__construct();
			
			// Determine which page should be served
			
			if(!empty($_GET['p']))
			{
				$this->page = $_GET['p'];
			}
			else
			{
				$this->page = 'home';
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
			}
			
		}
		
		private function navigation($ul = null)
		{
			
			if($_SESSION['user'])
			{
				
				// Navigation if a user is logged in
				
				$navigation = array(
					
					'home'		=>	'Home',
					//'profile'	=>	'Profile',
					'explore'	=>	'Explore',
					//'messages'	=>	'Messages',
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
			
			if($ul)
			{
				
				// Return it as a <ul>
				
				foreach($navigation as $link => $name)
				{
					
					$ul_navigation = $ul_navigation.'<li><a href="?p='.$link.'" id="'.$link.'">'.$name.'</a></li>'."\n";
					
				}
				
				return '<ul id="navigation">'.$ul_navigation.'</ul>';
			
			}
			else
			{
			
				// Return it as an array
				
				return $navigation;
				
			}
			
		}
		
		public function invoke()
		{
			
			// Head
			$title = ucfirst($this->page);
			$navigation = $this->navigation(1);
			require('html/head.html');
			
			// Body
			$Content = new Content;
			$Content->invoke();
			
			// Foot
			require('html/foot.html');
			
		}
		
	}

?>