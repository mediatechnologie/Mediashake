<?php

	session_start();
	
	require('includes/config.php');
	require('includes/functions.php');
	require('jsi.php');

	require('classes/database.class.php');
	require('classes/site.class.php');
	require('classes/content.class.php');
	require('classes/sidebar.class.php');
	require('classes/work.class.php');
	require('classes/image.class.php');
	
	$Site = new Site;
	$Site->invoke();

?>