<?php
/*
 * i18n.php
 * Set up translation support
 */

require('gettext.inc.php');

if(isset($_POST['lang']))
{
	$locale = $_POST['lang'];
	$_SESSION['language'] = $locale;
}
elseif(isset($_SESSION['language']))
{
	$locale = $_SESSION['language'];
}
else
{
	$locale = DEFAULT_LOCALE;
}

$domain = 'mediashake';
$directory = './languages';

// Set up gettext
setlocale(LC_ALL, $locale);
T_setlocale(LC_MESSAGES, $locale);

bindtextdomain($domain, $directory);

if (function_exists('bind_textdomain_codeset')) 
	bind_textdomain_codeset($domain, CHARSET);

textdomain($domain);