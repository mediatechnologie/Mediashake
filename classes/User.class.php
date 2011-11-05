<?php
/**
 * User class.
 */
class User
{
	public $username;
	public $firstname;
	public $lastname;
	
	public $gender;
	
	public $location;
	public $school;
	
	public $facebook;
	public $twitter;
	public $youtube;
	public $vimeo;
	
	public $joined;
	public $lastlogin;
	
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
				header('Location: '.SITE_URL.'showcase');
			}
			else
			{
				header('Location: '.SITE_URL.'error/login');
			}
		}
	}
	
	public function logout()
	{
		session_destroy();
		header('Location: '.SITE_URL);
	}
	
	public function __destruct()
	{
	
	}
}
