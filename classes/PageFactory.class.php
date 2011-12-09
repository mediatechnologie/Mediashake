<?php
/**
 * PageFactory class.
 * 
 * @implements Factory
 */
class PageFactory implements Factory
{
	private $db;
	
	public function __construct(Database $db)
	{
		$this->db = $db;
	}
	
	/**
	 * fetch function.
	 * Fetch a page based on the supplied arguments.
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function fetch($args = array())
	{
		$defaults = array(
			'type' => 'object',
			'slug' => null
		);
		extract(array_merge($defaults, $args));
		
		$sql = 'SELECT id, title, slug, created, content
				FROM pages
				WHERE slug = :sl
				LIMIT 1';
		$st = $this->db->prepare($sql);
		$st->bindValue(':sl', Database::sanitize($slug));
		
		if($type == 'array')
			$st->setFetchMode(PDO::FETCH_ASSOC);
		else
			$st->setFetchMode(PDO::FETCH_CLASS, 'page');
		
		if($st->execute())
		{
			return $st->fetch();
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * names function.
	 * Fetch the names of all the pages.
	 * @access public
	 * @return void
	 */
	public function names()
	{
		$sql = 'SELECT id, title, slug FROM pages ORDER BY slug';
		$statement = $this->db->query($sql);
		
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * snottebel function.
	 * The world's most important method.
	 * @access public
	 * @static
	 * @return Will
	 */
	public static function snottebel()
	{
		return 'Hai Will';
	}
	
	/**
	 * create function.
	 * Create a new page object.
	 * @access public
	 * @static
	 * @return void
	 */
	public static function create()
	{
		return new Page;
	}
}