<?php
	session_start();
	
	require('includes/config.php');
	require('includes/functions.php');
	require('autoload.php');
	
	$shake = new Site;
	echo $shake->invoke();