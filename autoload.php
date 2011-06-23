<?php
/**
**  @file autoloader.inc.php
**  @author imme‘mosol (programmer dot willfris at nl) 
**  @date 2010-12-06
**  Created: lun 2010-12-06, 14:05.13 CET
**  Last modified: lun 2010-12-06, 15:19.01 CET
**/

$classes_directory  =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes';
set_include_path(
	$classes_directory .
	PATH_SEPARATOR .
	get_include_path()
);
unset( $classes_directory );
spl_autoload_extensions( '.php, .inc.php, .class.php' );
spl_autoload_register();

function linux_namespaces_autoload ( $class_name )
    {
        /* use if you need to lowercase first char *
        $class_name  =  implode( DIRECTORY_SEPARATOR , array_map( 'lcfirst' , explode( '\\' , $class_name ) ) );/* else just use the following : */
        $class_name  =  implode( DIRECTORY_SEPARATOR , explode( '\\' , $class_name ) );
        static $extensions  =  array();
        if ( empty($extensions ) )
            {
                $extensions  =  array_map( 'trim' , explode( ',' , spl_autoload_extensions() ) );
            }
        static $include_paths  =  array();
        if ( empty( $include_paths ) )
            {
                $include_paths  =  explode( PATH_SEPARATOR , get_include_path() );
            }
        foreach ( $include_paths as $path )
            {
                $path .=  ( DIRECTORY_SEPARATOR !== $path[ strlen( $path ) - 1 ] ) ? DIRECTORY_SEPARATOR : '';
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
        throw new Exception( _( 'class ' . $class_name . ' could not be found.' ) );
    }
spl_autoload_register( 'linux_namespaces_autoload' , TRUE );
