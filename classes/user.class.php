<?php

	class User
	{
		
		public function uploadPicture($file)
		{
			
			// Configuration - Your Options
			$allowed_filetypes = array('.jpg','.gif','.bmp','.png', '.jpeg'); // These will be the types of file that will pass the validation.
			$max_filesize = 5242880; // Maximum filesize in BYTES (currently 5MB).
			$upload_path = './users/'; // The place the files will be uploaded to (currently a 'files' directory).
		
			$filename = $_SESSION['user']['id']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.

			// Check if the filetype is allowed, if not DIE and inform the user.
			if(!in_array($ext,$allowed_filetypes))
				die('The file you attempted to upload ('.$ext.') is not allowed.');
		
			// Now check the filesize, if it is too large then DIE and inform the user.
			if(filesize($file['tmp_name']) > $max_filesize)
				die('The file you attempted to upload is too large.');
		
			// Check if we can upload to the specified path, if not DIE and inform the user.
			if(!is_writable($upload_path))
				die('You cannot upload to the specified directory, please CHMOD it to 777.');
		
			// Upload the file to your specified path.
			if(move_uploaded_file($file['tmp_name'],$upload_path . $filename))
			{
				
			}
			
		}
		
	}

?>