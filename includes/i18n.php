<?php
/*
 * i18n.php
 * Set up translation support
 */

require('gettext.inc.php');

$domain = 'mediashake';
$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;
$directory = './languages';

// Set up gettext
setlocale(LC_ALL, $locale);
T_setlocale(LC_MESSAGES, $locale);

bindtextdomain($domain, $directory);

if (function_exists('bind_textdomain_codeset')) 
	bind_textdomain_codeset($domain, CHARSET);

textdomain($domain);