<?php
/**
 * User class.
 */
class User
{
	protected $username;
	protected $firstname;
	protected $lastname;
	
	protected $gender;
	
	protected $location;
	protected $school;
	
	/*
	 * Social stuff
	 */
	protected $facebook;
	protected $twitter;
	protected $youtube;
	protected $vimeo;
	
	protected $joined;
	protected $lastlogin;
	
	/**
	 * db
	 * Database handle.
	 * @var mixed
	 * @access protected
	 */
	protected $db;
	
	public function __construct()
	{
		$this->db = new Database;
	}
	
	public function getArray()
	{
		return array(
			'username' => $this->username,
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			
			'gender' => $this->gender,
			
			'location' => $this->location,
			'school' => $this->school,
			
			'facebook' => $this->facebook,
			'twitter' => $this->twitter,
			'youtube' => $this->youtube,
			'vimeo' => $this->vimeo,
			
			'joined' => $this->joined,
			'lastlogin' => $this->lastlogin
		);
	}
	
	/**
	 * Register function.
	 * Creates a user account
	 * @access public
	 * @param array $user_data
	 * @return int
	 */
	
	public function register($user_data)
	{
		extract($user_data);
		$md5_password = md5($password);
		
		$sql = "INSERT INTO `accounts` VALUES (NULL, :username, :password, :email, :firstname, :lastname, :gender, :location, :school, '', '', '', '', NOW(), '')";
		$st = $this->db->prepare($sql);
		$st->bindParam(':username', $username);
		$st->bindParam(':password', $md5_password);
		$st->bindParam(':email', $email);
		$st->bindParam(':firstname', $firstname);
		$st->bindParam(':lastname', $lastname);
		$st->bindParam(':gender', $gender);
		$st->bindParam(':location', $location);
		$st->bindParam(':school', $school);
		
		if( $st->execute() )
		{
			$this->login($username, $password);
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * login function.
	 * Log a user in.
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool
	 */
	public function login($username, $password)
	{
		
		$password = md5($password);
		
		$sql = 'SELECT * FROM `accounts`
				WHERE `username` = :un AND `password` = :pw LIMIT 1';
		$st = $this->db->prepare($sql);
		$st->bindParam(':un', $username);
		$st->bindParam(':pw', $password);
		
		if($st->execute())
		{
			$userdata = $st->fetch(PDO::FETCH_ASSOC);
			
			// Remove the password from the userdata, just to be on the safe side.
			unset($userdata['password']);
			$_SESSION['user'] = $userdata;
			
			return true;
		}
		else
		{
			throw new Exception('Login failed.');
			return false;
		}
	}
	
	/**
	 * logout function.
	 * Destroy the current session.
	 * @access public
	 * @return void
	 */
	public function logout()
	{
		unset($_SESSION['user']);
		return;
	}
	
	public function __get($prop)
	{
		return $this->$prop;
	}
	
	public function __set($prop, $val)
	{
		if(isset($this->$prop) and property_exists($this, $prop))
		{
			$this->$prop = $val;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function __destruct()
	{
	
	}
}
