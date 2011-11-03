<?php
/**
**  @file autoloader.inc.php
**  @author immeëmosol (programmer dot willfris at nl) 
**  @date 2010-12-06
**  Created: lun 2010-12-06, 14:05.13 CET
**  Last modified: lun 2010-12-06, 15:19.01 CET
**/

define('PS', PATH_SEPARATOR);
define('DS', DIRECTORY_SEPARATOR);

if(!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__).DS);

$classes_directory  =  ABSPATH . 'classes';
$includes_directory = ABSPATH . 'includes';
set_include_path(
	$classes_directory .
	PS .
	$includes_directory .
	PS .
	SMARTY_SYSPLUGINS_DIR .
	PS .
	SMARTY_PLUGINS_DIR .
	PS .
	get_include_path()
);
unset( $classes_directory, $includes_directory );
spl_autoload_extensions( '.php, .inc.php, .class.php' );
spl_autoload_register();

function linux_namespaces_autoload ( $class_name )
    {
        /* use if you need to lowercase first char *
        $class_name  =  implode( DS , array_map( 'lcfirst' , explode( '¥¥' , $class_name ) ) );/* else just use the following : */
        $class_name  =  implode( DS , explode( '¥¥' , $class_name ) );
        static $extensions  =  array();
        if ( empty($extensions ) )
            {
                $extensions  =  array_map( 'trim' , explode( ',' , spl_autoload_extensions() ) );
            }
        static $include_paths  =  array();
        if ( empty( $include_paths ) )
            {
                $include_paths  =  explode( PS , get_include_path() );
            }
        foreach ( $include_paths as $path )
            {
                $path .=  ( DS !== $path[ strlen( $path ) - 1 ] ) ? DS : '';
                foreach ( $extensions as $extension )
                    {
                        $file  =  $path . $class_name . $extension;
                        if ( file_exists( $file ) && is_readable( $file ) )
                            {
                                require $file;
                                return;
                            }
                    }
            }
        throw new Exception( 'class ' . $class_name . ' could not be found.' );
    }
spl_autoload_register( 'linux_namespaces_autoload' , TRUE );
