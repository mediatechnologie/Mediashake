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
			header('location: error');
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
		
		$sql = 'SELECT * FROM `accounts` WHERE `username` = :un AND `password` = :pw LIMIT 1';
		$st = $this->db->prepare($sql);
		$st->bindParam(':un', $username);
		$st->bindParam(':pw', $password);
		
		if($st->execute())
		{
			$userdata = $st->fetch(PDO::FETCH_ASSOC);
			
			// Remove the password from the userdata, just to be on the safe side.
			unset($userdata['password']);
			$_SESSION['user'] = $userdata;
			
			header('Location: '.SITE_URL.'/showcase');
			return true;
		}
		else
		{
			throw new Exception('Login failed.');
			header('Location: '.SITE_URL.'/error/login');
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
		session_destroy();
		header('Location: '.SITE_URL);
	}
	
	public function __clone()
	{
	
	}
	
	public function __destruct()
	{
	
	}
}
