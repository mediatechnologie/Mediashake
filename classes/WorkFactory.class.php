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
		
		$sql = 'SELECT * FROM work INNER JOIN accounts ON work.owner = accounts.id WHERE work.'.$type.' = :id LIMIT 1';
		$st = $this->db->prepare($sql);
		//$st->bindParam(':type', $type);
		$st->bindParam(':id', $id);
		
		if(! $st->execute())
		{
			throw new Exception(404);
		}
		
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
				break;
			}
			case 'json':
			{
				$output = $st->fetch(PDO::FETCH_ASSOC);
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
	 * @return void
	 */
	public function fetchAll($args = array())
	{
		$defaults = array(
			// Which column from the table to sort by
			'sort_by' => 'date',
			// The maximum amount of rows to be returned
			'limit' => 50,
			// What kind of data we should output,
			// Array or JSON
			'output_type' => 'array'
		);
		$args = array_merge($defaults, $args);
		extract($args);

		$sql = "SELECT * FROM `work` ORDER BY ".$sort_by." DESC LIMIT ".$limit;
		$return = array();

		if($result = $this->db->query($sql))
		{
			$output = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach($output as & $row)
			{
				$row['title'] = urldecode($row['title']);
				
				if($row['type'] == 2) // Video
				{
					$row['filename'] = 'images/video.jpg';
				}
				else // Image
				{
					$row['filename'] = 'uploads/'.$row['filename'];
				}
				
				$row['title'] = str_replace("'", "&#39;", $row['title']);
				$row['title'] = str_replace('"', '&quot;', $row['title']);
				
				$row['description'] = str_replace("'", "&#39;", 
					$row['description']);
				$row['description'] = str_replace('"', '&quot;', 
					$row['description']);
			}
			
			unset($result);
		}
		
		if($args['output_type'] == 'json')
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
}