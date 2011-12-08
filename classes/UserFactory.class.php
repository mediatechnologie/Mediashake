<?php
/**
 * UserFactory class.
 */
class UserFactory
{
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
	
	public function fetch($args = array())
	{
		$defaults = array(
			'username' => null,
			'id' => 0,
			'output_type' => 'object'
		);
		extract(array_merge($defaults, $args));
		
		$sql = 'SELECT accounts.id, username, firstname, lastname, gender, location, 
				facebook, twitter, youtube, vimeo, 
				joined, lastlogin, schools.name
				FROM accounts 
				LEFT JOIN schools ON accounts.school = schools.id';
	//			WHERE accounts.id = 3';
		
		$st = $this->db->prepare($sql);
		
		if($output_type == 'object')
		{
			//$st->setFetchMode(PDO::FETCH_INTO, self::create());
			return $st->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	public function fetchAll($args = array())
	{
		$defaults = array(
			'start' => 0,
			'limit' => 20,
			'output_type' => 'array'
		);
		extract(array_merge($defaults, $args));
		
		$sql = 'SELECT accounts.id, username, firstname, lastname, gender, location, schools.name
				FROM accounts
				LEFT JOIN schools ON  accounts.school = schools.id 
				LIMIT 0, 20';
		
		$st = $this->db->prepare($sql);
		//$st->bindParam(':start', $start);
		//$st->bindParam(':limit', $limit);
		
		if( ! $st->execute())
			throw new Exception('Database query failed.');
		
		$ot = $output_type;
		if($ot == 'array')
			return $st->fetchAll(PDO::FETCH_ASSOC);
		elseif($ot == 'json')
			return json_encode($st->fetchAll(PDO::FETCH_ASSOC));
	}
	
	public static function formatUsername($un)
	{
		return str_ireplace(
			array( ' ', '' ),
			array( '-', '' ),
			$un
		);
	}
	
	public function add($user)
	{
		if(!($user instanceof User))
			throw new Exception('Invalid User object.');
		
		$data = $user->getArray();
		
		$sql = 'INSERT INTO users
				(username, password, firstname, lastname, email, school)
				VALUES (:un, :pw, :fn, :ln, :em, :sc)';
		$st = $this->prepare($sql);
		
		$st->bindParam(':un', $user->getName());
		
		
		if($st->execute())
			return true;
		else
			return false;
	}
	
	public function update($user)
	{
		return;
	}
	
	public function create()
	{
		return new User;
	}
}