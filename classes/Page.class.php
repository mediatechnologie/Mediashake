<?php
/**
 * Page class.
 * a generic page,
 * for arbitrary content
 */
class Page
{
	/**
	 * id
	 * the (unique) page identifier
	 * @var mixed
	 * @access public
	 */
	public $id;
	
	/**
	 * title
	 * the page title
	 * @var string
	 * @access public
	 */
	public $title = '';
	
	/**
	 * slug
	 * the (unique) page slug
	 * @var string
	 * @access public
	 */
	public $slug = '';
	
	/**
	 * content
	 * the page content
	 * @var string
	 * @access public
	 */
	public $content = '';
	
	public function __construct()
	{
	
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getContent()
	{
		return $this->content;
	}
	
	public function setContent($content)
	{
		$this->content = $content;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getSlug()
	{
		return $this->slug;
	}
	
	public function setSlug($slug)
	{
		$this->slug = $slug;
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
}