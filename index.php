<?php

	session_start();

	require('includes/config.php');
	require('autoload.php');

	// require('includes/functions.php');
	// require('jsi.php');
	
	$Site = new Site;
	$Site->invoke();

?>