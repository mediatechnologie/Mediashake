<?php
/**
 * config.sample.php
 * configuration file
 * Defines contants for the app.
 */

// The database host name, this is usually localhost.
define('DB_HOST', 'localhost');

// The database user
define('DB_USER', 'someone');

// The user's password
define('DB_PASSWORD', 'sekret');

// The name of the database
define('DB_NAME', 'mediashake');

// The title of your site
define('SITE_NAME', 'Mediashake');

// The root url of the site, without a trailing slash
define('SITE_URL', 'http://mediashake');

// Path to the upload directory.
define('UPLOAD_PATH', './uploads/');

// The character set for output.
define('CHARSET', 'utf-8');

// Smarty constants
define('SMARTY_DIR', 'classes/');
define('SMARTY_SYSPLUGINS_DIR', 'includes/smarty_sysplugins/');
define('SMARTY_PLUGINS_DIR', 'includes/smarty_plugins/');