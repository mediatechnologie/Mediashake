<?php

	session_start();

	require('includes/config.php');
	require('includes/functions.php');
	require('autoload.php');
	
	$Site = new Site;
	$Site->invoke();

?>