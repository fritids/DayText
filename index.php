<?php
    /** Define ABSPATH as this file's directory */
    define( 'ABSPATH', dirname(__FILE__) . '/' );
    
    /** Enable Verbose Mode */
    define('VMODE',true);
    if (file_exists( ABSPATH . 'dt-config.php') ) {
        require_once( ABSPATH . 'dt-config.php' );
    }else{
        require_once(ABSPATH.'dt-settings.php');
    }
?>
