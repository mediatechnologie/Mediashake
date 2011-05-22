<?php

	session_start();

	require_once('includes/config.php');
	require_once('autoload.php');
	
	$Site = new Site;
	$Site->invoke();

?>