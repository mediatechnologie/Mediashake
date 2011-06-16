<?php

	function checklogin()
	{
		if(!$_SESSION['user'])
			die('You have to be logged in to comment or rate work.');
	}
	
?>