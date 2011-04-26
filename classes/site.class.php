<?php

	class Site
	{
		
		private $page;
		
		public function __construct()
		{
			if(!empty($_GET['page']))
			{
				$this->page = $_GET['page'];
			}
			else
			{
				$this->page = 'Home';
			}
		}
		
		public function invoke()
		{
			$title = $this->page.' | Mediashake';
			require_once('html/main.html');
		}
		
	}

?>