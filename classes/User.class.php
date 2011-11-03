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
	
	public function login()
	{
		
	}
	
	public function logout()
	{
		
	}
	
	public function __destruct()
	{
	
	}
}
