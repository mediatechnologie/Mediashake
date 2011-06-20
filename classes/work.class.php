<?php

	class Work extends Database
	{
		
		public function __construct()
		{
			
			parent::__construct();
			
		}
		
		public function newVideo()
		{
			
			$title = urlencode($_POST['title']);
			$description = urlencode($_POST['description']);
			$url = $_POST['url'];
			
			$youtube = strpos($url, 'youtube');
			$vimeo = strpos($url, 'vimeo');
			
			if($youtube)
			{
				$code = explode('?v=', $url);
				$code = explode('&', $code[1]);
				$code = $code[0];
				$service = 'youtube';
			}
			elseif($vimeo)
			{
				$code = end(explode('/', $url));
				$service = 'vimeo';
			}
			else
			{
				header('location: index.php?status=error');
			}
	
			$owner = $_SESSION['user']['id'];
			$school = $_SESSION['user']['school'];
			
			// Create thumbnail
			//copy('video.jpg', '/work/'.$code.'.jpg');
			//rename('video.jpg', 'uploads/'.$code.'.jpg');
			
			$this->db->query("INSERT INTO `work` VALUES (NULL, '2', '$owner', '$school', '$title', '".$service.":".$code."', '$description', '0', '0', '0', NOW());");
				
			header("location: index.php?status=success");
			die();
			
		}
		
		public function upload()
		{
			// Configuration - Your Options
			$allowed_filetypes = array('.jpg','.gif','.bmp','.png', '.jpeg'); // These will be the types of file that will pass the validation.
			$max_filesize = 5242880; // Maximum filesize in BYTES (currently 5MB).
			$upload_path = './uploads/'; // The place the files will be uploaded to (currently a 'files' directory).
		
			$filename = $_SESSION['user']['id'].'_'.$_FILES['userfile']['name']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

			// Check if the filetype is allowed, if not DIE and inform the user.
			if(!in_array($ext,$allowed_filetypes))
				die('The file you attempted to upload ('.$ext.') is not allowed.');
		
			// Now check the filesize, if it is too large then DIE and inform the user.
			if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
				die('The file you attempted to upload is too large.');
		
			// Check if we can upload to the specified path, if not DIE and inform the user.
			if(!is_writable($upload_path))
				die('You cannot upload to the specified directory, please CHMOD it to 777.');
		
			// Upload the file to your specified path.
			if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $filename))
			{
				$title = urlencode($_POST['title']);
				$description = urlencode($_POST['description']);
				$file_location = $upload_path . $filename;
				$file_location = str_replace('./', '', $file_location);
				$owner = $_SESSION['user']['id'];
				$school = $_SESSION['user']['school'];
		
				$this->db->query("INSERT INTO `work` VALUES (NULL, '$type', '$owner', '$school', '$title', '$filename', '$description', '0', '0', '0', NOW());");
				
				header("location: index.php?status=success");
			}
		}
		
		public function newWebsite()
		{
			
			
			
		}
		
		public function fetchWork($parameters = null)
		{
			
			$query = "SELECT * FROM `work` ORDER BY `date` DESC";
			$return = array();

			if ($result = $this->db->query($query)) {
			
				while ($row = $result->fetch_assoc()) {
					
					$title = urldecode($row['title']);
					if(!file_exists('uploads/'.$row['filename']))
					{
						$row['filename'] = 'video.jpg';
					}
					
					$title = str_replace("'", "&#39;", $title);
					$title = str_replace('"', '&quot;', $title);
					
					$description = str_replace("'", "&#39;", $row['description']);
					$description = str_replace('"', '&quot;', $description);
					
					$return[] = array(
						$row['id'],
						$row['type'],
						$row['owner'],
						$title,
						$row['filename'],
						$row['description'],
						$row['date'],
						$row['school']
					);
				}
				
				$result->free();
			}
			
			return json_encode($return);
			
		}
		
		public function showWork($parameters = null)
		{
			
			// Parameters
			
				// Max items
				if(!empty($parameters['max']))
					$max = $parameters['max'];
				else
					$max = 5;
					
				// Rating
				$order = 'date';
				switch($parameters['order'])
				{
					case 'rating':
						$order = 'rating';
						break;
				}
				
				//
				if(!empty($parameters['user']))
					$user = " WHERE `owner` = '".$parameters['user']."'";
				
				
			
			$query = "SELECT * FROM `work`".$user." ORDER BY `".$order."` DESC LIMIT 0,".$max;

			if ($result = $this->db->query($query)) {
			
				while ($row = $result->fetch_assoc()) {
					
					if(!file_exists('uploads/'.$row['filename']))
					{
						$row['filename'] = 'video.jpg';
					}
					
					$return .= '
					<li>
						<a href="?p=work&id='.$row['id'].'">
							<img src="uploads/'.$row['filename'].'"/>
						</a>
					</li>
					'."\n";
					//printf ("%s (%s)\n", $row["Name"], $row["CountryCode"]);
				}
				
				$result->free();
			}
			
			return '<ul id="most-popular">'.$return.'</ul>';
			
		}
		
		public function video()
		{
			
			
			
		}
		
		private function getname($id)
		{
			
			if ($result = $this->db->query("SELECT * FROM `accounts` WHERE `id` = '$id' LIMIT 0,1"))
			{
				while ($row = $result->fetch_assoc())
				{
					return $row['firstname'].' '.$row['lastname'];
				}
			}
			
			//return 'Farid el Nasire';
		}
		
		public function showComments()
		{
			$id = stripslashes($_GET['id']);
			
			$query = "SELECT * FROM `comments` WHERE `work` = '$id' ORDER BY `date` DESC";

			if ($result = $this->db->query($query)) {
			
				while ($row = $result->fetch_assoc()) {
					
					$file = 'images/users/'.$_SESSION['user']['id'].'.jpg';
			
					if(file_exists($file))
					{
						$user_image = $_SESSION['user']['id'];
					}
					else
					{
						$user_image = 'noavatar';
					}
					
					$return .= '
					<li>
						<img src="images/users/'.$user_image.'.jpg"/>
						<p class="author">'.$this->getname($row['author']).'</p>
						'.$row['comment'].'
					</li>
					'."\n";
					//printf ("%s (%s)\n", $row["Name"], $row["CountryCode"]);
				}
				
				$result->free();
			}
			
			return $return;
			
		}
		
	}

?>