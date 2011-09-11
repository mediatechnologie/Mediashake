<?php

	function checklogin()
	{
		if(empty($_SESSION['user']))
			echo('You have to be logged in to comment or rate work.');
	}
	
?>