<?php
/**
 * WorkFactory class.
 */
class WorkFactory
{
	/**
	 * db
	 * database object
	 * @var mixed
	 * @access protected
	 */
	protected $db;
	
	public function __construct()
	{
		$this->db = new Database;
	}
	
	public function add($work)
	{
		if(!($work instanceof Work))
			throw new Exception('Did not receive a valid Work object.');
		
		$sql = 'INSERT INTO work ( title, description, type, owner, school, filename )
				VALUES ( :title, :description, :type, :owner, :school, :filename )';
		$values = array('title', 'description', 'type', 'owner', 'school', 'filename');
		
		$st = $this->db->prepare($sql);
		
		foreach($work->getArray() as $wprop => $wval)
		{
			if(in_array($wprop, $values))
			{
				$st->bindValue(':'.(string) $wprop, $wval);
			}
		}
		
		if($st->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * fetch function.
	 * get a single work item from the database
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function fetch($args = array())
	{
		$defaults = array(
			// The column for our WHERE clause
			'type' => 'id',
			// The identifier from that column we should select
			'id' => 1,
			// What kind of data we should output
			'output_type' => 'object',
		);
		$args = array_merge($defaults, $args);
		extract($args);
		
		$sql = 'SELECT work.id, type, title, work.description filename, rating, votes, views, date,
				accounts.username, accounts.firstname, accounts.lastname
				FROM work INNER JOIN accounts ON work.owner = accounts.id
				WHERE work.'.Database::sanitize($type).' = :id
				LIMIT 1';
		
		$st = $this->db->prepare($sql);
		//$st->bindParam(':type', $type);
		$st->bindParam(':id', $id);
		
		if(! $st->execute())
			throw new Exception('Database query failed.');
		
		if($st->rowCount() == 0)
			throw new Exception(404);
		
		switch($output_type)
		{
			case 'object':
			{
				$output = $st->fetch(PDO::FETCH_CLASS, 'Work');
				break;
			}
			case 'array':
			{
				$output = $st->fetch(PDO::FETCH_ASSOC);
				if($output['type'] == 0)
					$output['filename'] = UPLOAD_PATH.$output['filename'];
				break;
			}
			case 'json':
			{
				$output = $st->fetch(PDO::FETCH_ASSOC);
				if($output['type'] == 0)
					$output['filename'] = UPLOAD_PATH.$output['filename'];
				$output = json_encode($output);
				break;
			}
		}
		
		return $output;
	}
	
	/**
	 * fetchAll function.
	 * get multiple work items from the database
	 * @access public
	 * @param array $args (default: array())
	 * @return mixed
	 */
	public function fetchAll($args = array())
	{
		$defaults = array(
			// Which column from the table to sort by
			'sort_by' => 'date',
			// The maximum amount of rows to be returned
			'limit' => 50,
			// What kind of data we should output, an Array or JSON
			'output_type' => 'array'
		);
		$args = array_merge($defaults, $args);
		extract($args);
		
		$sql = 'SELECT * FROM work ORDER BY :sort DESC LIMIT :limit';
		$st = $this->db->prepare($sql);
		
		$st->bindParam(':sort', $sort_by);
		$st->bindParam(':limit', $limit, PDO::PARAM_INT);
		
		if($st->execute())
		{
			$output = $st->fetchAll(PDO::FETCH_ASSOC);
			foreach($output as & $w)
			{
				if($w['type'] == 0)
					$w['filename'] = substr(UPLOAD_PATH, 2).$w['filename'];
			}
		}
		else
		{
			throw new Exception('Database query failed. '.$st->errorInfo());
		}
		
		if($output_type == 'json')
		{
			return json_encode($output);
		}
		else
		{
			return $output;
		}
	}
	
	/**
	 * create function.
	 * 
	 * @access public
	 * @static
	 * @return object
	 */
	public static function create()
	{
		return new Work;
	}
	
	/**
	 * Adds a comment to the database
	 * 
	 * @access public
	 * @return bool
	 */
	public function addComment($work, $comment)
	{
		if($comment == '')
			return false;
		
		if(!is_int($work))
			return false;
		
		if(!is_int($_SESSION['user']['id']))
			throw new Exception('Invalid user id.');
			
		$st = $this->db->prepare('INSERT INTO `comments` ( work, author, comment ) VALUES ( :work, :author, :comment ');
		
		$st->bindParam(':work', $work);
		$st->bindParam(':author', $_SESSION['user']['id']);
		$st->bindValue(':content', Database::sanitize($comment));
		
		if($st->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Fetches all comments for certain work (by work.id)
	 * 
	 * @access public
	 * @return array
	 */
	public function fetchComments($id)
	{
		if(!is_int($id) or !is_numeric($id))
			return false;
			
		$sql = 'SELECT * FROM `comments` INNER JOIN accounts ON comments.author=accounts.id WHERE `work` = :id';
		$st = $this->db->prepare($sql);
		$st->bindParam(':id', $id);
		
		if($st->execute())
		{
			return $st->fetchAll(PDO::FETCH_ASSOC);
		}
		else
		{
			return false;
		}
	}
}