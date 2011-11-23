<?php
/**
 * View class.
 *
 * Generic View object, holds key-values for a template.
 * 
 */
class View
{
	protected $data = array();
	
	protected $file;
	
	public function __construct($data = null)
	{
		if(is_array($data))
			$this->addData($data);
	}
	
	public function assign($key, $val)
	{
		if(is_string($key) or is_int($key))
		{
			$this->data[ $key ] = $val;
		}
	}
	
	public function getData($key)
	{
		if(array_key_exists($key, $this->data))
		{
			return $this->data[ $key ];
		}
		else
		{
			return false;
		}
	}
	
	public function setData($key, $val)
	{
		$this->data[ $key ] = $val;
	}
	
	public function addData($data)
	{
		if(is_array($data))
		{
			$this->data = array_merge($this->data, $data);
		}
		else
		{
			return false;
		}
	}
	
	public function dataExists($key)
	{
		return array_key_exists($key, $this->data);
	}
	
	public function setFile($file)
	{
		$this->file = $file;
	}
	
	public function getFile()
	{
		return $this->file;
	}
	
	public function display()
	{
		if(file_exists($this->file))
		{
			extract($this->data);
			include($this->file);
		}
		else
		{
			throw new Exception('Template file not found.');
		}
	}
	
	public function fetch($file = null)
	{
		ob_start();
		$this->display();
		$output = ob_get_clean();
		
		return $output;
	}
}