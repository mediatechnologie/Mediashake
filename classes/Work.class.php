<?php
/**
 * Work class.
 * 
 * @extends Page
 */
class Work extends Page
{
	/*
	 * We inherit $id, $title, $slug and $content
	 * from the Page class.
	 */
	
	public $type = 0;
	
	public $owner;
	public $school = 0;
	
	public $filename;
	public $description = '';
	
	public $rating;
	public $votes;
	public $views;
	public $date;
	
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Decode the title
		$this->title = urldecode($this->title);
	}
	
	/**
	 * addVideo function.
	 * Parse a video URL and set it as a property.
	 * @access public
	 * @param mixed $url
	 * @return bool
	 */
	public function addVideo($url)
	{
		// Regex patterns to recognize valid Youtube and Vimeo URL's
		$ytpattern = '~^http(s?)://(www\.)?youtube.com/watch\?([A-Za-z_&=0-9?]+)?v=[A-Za-z_0-9]+~';
		$vimeopattern = '~^http(s?)://(www\.)?vimeo.com/([0-9]){3,12}~';
		
		// See if we match a Youtube URL
		if(preg_match($ytpattern, $url))
		{
			parse_str( parse_url( $url, PHP_URL_QUERY ), $ytparams );
			if(!empty($ytparams['v']))
				$vid = $ytparams['v'];
			
			$this->filename = 'youtube:'.$vid;
		}
		// Or see if it matches a Vimeo URL
		elseif(preg_match($vimeopattern, $url))
		{
			$purl = parse_url($url);
			$vid = substr($purl['path'], 1);
			
			$this->filename = 'vimeo:'.$vid;
		}
		else
		{
			throw new Exception('Invalid video URL. Youtube and Vimeo URLs are accepted.');
		}
		
		return true;
	}
	
	/**
	 * addWebsite function.
	 * Check a web URL and set it as a property.
	 * @access public
	 * @param mixed $url
	 * @return bool
	 */
	public function addWebsite($url)
	{
		// Crazy-ass regex from Gruber again. {@link http://daringfireball.net/2010/07/improved_regex_for_matching_urls}
		$pattern = '~(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))~';
		
		if(preg_match($pattern, $url))
		{
			$this->filename = $url;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * addFile function.
	 * Write a file to disk and set it as a property.
	 * @access public
	 * @return bool
	 *
	 * @todo Move this to a "File" class?
	 */
	public function addFile($file)
	{
		// These will be the types of file that will pass the validation.
		$allowed_filetypes = array(
			'jpg', 'gif', 'bmp', 'png', 'jpeg',
			'doc', 'rtf', 'odf'
		);
		
		// Maximum filesize in BYTES (currently 5MB).
		$max_filesize = 5242880;
		
		// Get the name of the file (including file extension).
		$filename = $_SESSION['user']['id'].'_'.$file['name'];
		
		// Get the extension from the filename.
		$ext = substr($filename, strpos($filename,'.') + 1, strlen($filename) - 1);

		// Check if the filetype is allowed, if not DIE and inform the user.
		if(!in_array($ext, $allowed_filetypes))
			throw new Exception('The file you attempted to upload ('.$ext.') is not allowed.');
	
		// Now check the filesize, if it is too large then DIE and inform the user.
		if(filesize($file['tmp_name']) > $max_filesize)
			throw new Exception('The file you attempted to upload is too large.');
	
		// Check if we can upload to the specified path, if not DIE and inform the user.
		if(!is_writable(UPLOAD_PATH))
			throw new Exception('You cannot upload to the specified directory, please CHMOD it.');
	
		// Upload the file to the specified path.
		if(move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename))
		{
			$this->filename = $filename;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function addImage($file)
	{
		//$this->addFile($file);
	}
	
	/**
	 * getArray function.
	 * Retun class properties in an associative array.
	 * @access public
	 * @return array
	 */
	public function getArray()
	{
		return array(
			'title' => $this->title,
			'description' => $this->description,
			'date' => $this->date,
			
			'type' => $this->type,
			'owner' => $this->owner,
			'school' => $this->school,
			'filename' => $this->filename,
			
			'rating' => $this->rating,
			'votes' => $this->votes,
			'views' => $this->views
		);
	}
}