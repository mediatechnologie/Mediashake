<?php
/*
 * jsi.php
 * Javascript endpoint.
 * @deprecated
 */
session_start();

/*
	--- GET REQUESTS ---
		
	--- POST REQUESTS ---
	- login
*/

// Establish database connection
require('includes/config.php');
require('classes/Database.class.php');
$db = new Database;

function getname($id)
{
	if ($result = $db->query("SELECT * FROM `accounts` WHERE `id` = '$id' LIMIT 0,1"))
	{
		while ($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			return $row['firstname'].' '.$row['lastname'];
		}
	}
	
	//return 'Farid el Nasire';
}

if(!empty($_GET['action']))
	$get_action = $_GET['action'];
else
	$get_action = null;
	
switch($get_action)
{
	
	case 'register':
		extract($_POST);
		$password = md5($password);
		$db->query("INSERT INTO `accounts` VALUES (NULL, '$username', '$password', '$firstname', '$lastname', '$gender', '$location', '$school', '', '', '', '', NOW(), NOW());");
		
		if ($result = $db->query("SELECT * FROM `accounts` WHERE `username` = '$username' LIMIT 0,1"))
		{
			while ($row = $result->fetch(PDO::FETCH_ASSOC))
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

if(!empty($_POST['action']))
	$post_action = $_POST['action'];
else
	$post_action = null;

switch($post_action)
{
	
	case 'login':
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		
		if ($result = $db->query("SELECT * FROM `accounts` WHERE `username` = '$username' AND `password` = '$password' LIMIT 0,1"))
		{
		
			if($result->rowCount() == 1)
			{
				while ($row = $result->fetch(PDO::FETCH_ASSOC))
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
		
			unset($result);
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
	
	case 'love':
	
		$id = $_POST['id'];
		$user = $_SESSION['user']['id'];
		
		$query = "SELECT * FROM `loves` WHERE `user` = '$user' AND `work` = '$id' LIMIT 0,1";
		
		if ($result = $db->query($query)) {
			if($result->rowCount() === 0)
			{
				// Add one love to work
				$query_work = "SELECT * FROM `work` WHERE `id` = '$id' LIMIT 0,1";
				if($work = $db->query($query_work))
				{
					$row = $work->fetch(PDO::FETCH_ASSOC);
					$loves = $row['votes'];
					$new_loves = $loves+1;
					$new_loves_query = "UPDATE `work` SET `votes` = '$new_loves' WHERE `id` = $id";
					$db->query($new_loves_query);
				
					// Save it to the user's loves
					$query = "INSERT INTO `loves` VALUES (NULL, '$user', '$id');";
					$db->query($query);
					
					echo '1';
				}
			}
			else
			{
				echo '0';
			}
		}
		/*echo 'User: '.$user.' - '.$_SESSION['user']['username']."\n".
							'Work: '.$id;*/
		break;
	
}