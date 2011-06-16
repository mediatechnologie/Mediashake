<?php

	class Database
	{
		
		public $db;
	
		public function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
			if (mysqli_connect_error())
				die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		
	}

?>