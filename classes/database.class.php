<?php

	class Database extends PDO
	{
		
		public function __construct()
		{
			try
			{
				$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST;
				parent::__construct( $dsn , DB_USER , DB_PASSWORD );
			}
			catch(PDOException $e)
			{
				echo 'Could not connect to the database.';
				exit;
			}
		}
		
	}
	
?>