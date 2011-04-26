<?php

	session_start();

	require_once('includes/config.php');
	
	require_once('classes/site.class.php');
	
	$Site = new Site;
	$Site->invoke();

?>