<?php

	session_start();
	
	require('includes/config.php');

	require('classes/Site.class.php');
	require('classes/Database.class.php');
	require('classes/User.class.php');
	
	$site = new Site;
	$site->invoke();

?>