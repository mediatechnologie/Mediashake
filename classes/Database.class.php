<?php
/**
 * Database class.
 * Mediashake Database object.
 * 
 * @extends PDO
 */
class Database extends PDO
{
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		try
		{
			$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST;
			parent::__construct( $dsn , DB_USER , DB_PASSWORD );
		}
		catch(PDOException $e)
		{
			echo 'Could not connect to the database. '.$e->getMessage();
			exit;
		}
	}
	
	/**
	 * sanitize function.
	 * Sanitize user input.
	 * @access public
	 * @static
	 * @param mixed $input
	 * @return void
	 */
	public static function sanitize($input)
	{
		$flags = ENT_QUOTES;
		if(defined('ENT_HTML5'))
			$flags |= ENT_HTML5;
		return htmlentities($input, $flags, CHARSET);
	}
}