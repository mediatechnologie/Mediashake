<?php

	session_start();
	/*
		--- GET REQUESTS ---
			
		--- POST REQUESTS ---
		- login
	*/
	
	// Establish database connection
	require('includes/config.php');
	require('classes/database.class.php');
	$database = new Database;
	$db = $database->db;


		function getname($id)
		{
			
			$database = new Database;
			$db = $database->db;
			
			if ($result = $db->query("SELECT * FROM `accounts` WHERE `id` = '$id' LIMIT 0,1"))
			{
				while ($row = $result->fetch_assoc())
				{
					return $row['firstname'].' '.$row['lastname'];
				}
			}
			
			//return 'Farid el Nasire';
		}

	
	switch($_GET['action'])
	{
		
		case 'register':
			extract($_POST);
			$password = md5($password);
			$db->query("INSERT INTO `accounts` VALUES (NULL, '$username', '$password', '$firstname', '$lastname', '$gender', '$location', '$school', '', '', '', '', NOW(), NOW());");
			
			if ($result = $db->query("SELECT * FROM `accounts` WHERE `username` = '$username' LIMIT 0,1"))
			{
				while ($row = $result->fetch_assoc())
				{
					
					$_SESSION['user']['id'] = $row['id'];
					$_SESSION['user']['firstname'] = $row['firstname'];
					$_SESSION['user']['lastname'] = $row['lastname'];
					$_SESSION['user']['gender'] = $row['gender'];
					$_SESSION['user']['location'] = $row['location'];
					$_SESSION['user']['school'] = $row['school'];
					$_SESSION['user']['facebook'] = $row['facebook'];
					$_SESSION['user']['twitter'] = $row['twitter'];
					$_SESSION['user']['youtube'] = $row['youtube'];
					$_SESSION['user']['vimeo'] = $row['vimeo'];
					
					if(!empty($_FILES['picture']))
					{
						//echo 'upload picture:';
						$User = new User;
						$User->uploadPicture($_FILES['picture']);
					}
				}
			}
						header('location: index.php');
			
			break;
		
	}
	
	switch($_POST['action'])
	{
		
		case 'login':
			$username = $_POST['username'];
			$password = md5($_POST['password']);
			
			if ($result = $db->query("SELECT * FROM `accounts` WHERE `username` = '$username' AND `password` = '$password' LIMIT 0,1"))
			{
			
				if($result->num_rows == 1)
				{
					while ($row = $result->fetch_assoc())
					{
						
						$_SESSION['user']['id'] = $row['id'];
						$_SESSION['user']['firstname'] = $row['firstname'];
						$_SESSION['user']['lastname'] = $row['lastname'];
						$_SESSION['user']['gender'] = $row['gender'];
						$_SESSION['user']['location'] = $row['location'];
						$_SESSION['user']['school'] = $row['school'];
						$_SESSION['user']['facebook'] = $row['facebook'];
						$_SESSION['user']['twitter'] = $row['twitter'];
						$_SESSION['user']['youtube'] = $row['youtube'];
						$_SESSION['user']['vimeo'] = $row['vimeo'];
						
					}
					echo 'OK';
				}
				else
				{
					echo 'WRONG';
					//echo $username.' -- '.$_POST['username'].' :: '.$_POST['password'];
				}
			
				$result->close();
			}
			
			break;
		
		case 'comment':
			if(!empty($_SESSION['user']))
			{
				$user_id = $_SESSION['user']['id'];
				$work = stripcslashes($_POST['work']);
				$comment = urldecode($_POST['comment']);
				$db->query("INSERT INTO `comments` VALUES (NULL, '$work', '$user_id', '$comment', NOW());");				
			}
			break;
		
		case 'rate':
			$id = stripslashes($_POST['work']);
			$vote = $_POST['rating'];
			
			$query = "SELECT * FROM `work` WHERE `id` = '$id' LIMIT 0,1";

			if ($result = $db->query($query)) {
			
				while ($row = $result->fetch_assoc()) {
					$rating = $row['rating'];
					$votes = $row['votes'];
					$total_rating = $rating*$votes;
					$new_rating = ($total_rating+$vote)/($votes+1);
					$new_votes = $votes+1;
					$db->query("UPDATE  `work` SET  `rating` =  '$new_rating', `votes` = '$new_votes' WHERE  `id` = '$id';");
					echo $id;
				}
				
				$result->free();
			}
			break;
		
	}
?>