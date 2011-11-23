<?php
/*
 * index.php
 */

define('ABSPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

session_start();

require('includes/config.php');
require('includes/functions.php');
require('includes/autoload.php');
require('includes/i18n.php');

$shake = new Site;
echo $shake->invoke();