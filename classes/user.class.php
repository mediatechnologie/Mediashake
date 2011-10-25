<?php

	class User extends Site
	{
		
		protected $db;
		
		public function __construct()
		{
			$this->db = new Database;
		}
		
		public function login($username, $password)
		{
			$password = md5($password);
			if($result = $this->db->query("SELECT * FROM `accounts` WHERE `username` = '$username' AND `password` = '$password' LIMIT 0,1"))
			{
				if($result->rowCount() == 1)
				{
					while ($row = $result->fetch(PDO::FETCH_ASSOC))
					{
						$_SESSION['user']['id'] = $row['id'];
						$_SESSION['user']['firstname'] = $row['firstname'];
						$_SESSION['user']['lastname'] = $row['lastname'];
						$_SESSION['user']['gender'] = $row['gender'];
						$_SESSION['user']['location'] = $row['location'];
						$_SESSION['user']['school'] = $row['school'];
						$_SESSION['user']['facebook'] = $row['facebook'];
						$_SESSION['user']['twitter'] = $row['twitter'];
						$_SESSION['user']['youtube'] = $row['youtube'];
						$_SESSION['user']['vimeo'] = $row['vimeo'];
					}
					header('Location: '.SITE_ROOT.'showcase');
				}
				else
				{
					header('Location: '.SITE_ROOT.'error/login');
				}
			}
		}
		
		public function logout()
		{
			session_destroy();
			header('Location: '.SITE_ROOT);
		}
		
		public function getUserByUsername($username)
		{
			if($result = $this->db->query("SELECT * FROM `accounts` WHERE `username` = '$username' LIMIT 0,1"))
			{
				if($result->rowCount() != 0)
				{
					while ($row = $result->fetch(PDO::FETCH_ASSOC))
					{
						return array(
							'id'									=>	$row['id'],
							'firstname'		=>	$row['firstname'],
							'lastname'			=>	$row['lastname'],
							'gender'					=>	$row['gender'],
							'location'			=>	$row['location'],
							'school'					=>	$row['school'],
							'facebook'			=>	$row['facebook'],
							'twitter'				=>	$row['twitter'],
							'youtube'				=>	$row['youtube'],
							'vimeo'						=>	$row['vimeo']
						);
					}
				}
				else
				{
					return 'No users with that username have been found.';
				}
			}
		}
		
	}

?>