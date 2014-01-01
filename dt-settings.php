<?php
    require_once(ABSPATH.'dt-includes/functions.php');
    $unpassed = array();
    if(!dt_check_php_version()){
        array_push($unpassed,"php_version");
    }
    if(!count($unpassed)==0){
        die("");
    }else{
        if(!file_exists(ABSPATH.'dt-config.php')){
            prepareSettings();
        }
    }
?>