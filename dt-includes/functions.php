<?php
    function dt_check_php_version(){
        $php_version = phpversion();
        if(ord($php_version) >= 53){
            return true;
        }else{
            return false;
        }
    }
    function prepareSettings(){
        $file_handle = fopen(ABSPATH."dt-includes/settings.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
        $path = $_SERVER['PHP_SELF'];
        $path = substr($path,0,strlen($path)-9);
        $path .= "dt-admin/setup-config.php";
        print("<a href=\"$path\" class=\"button button-large\">创建配置文件</a></p></body></html>");
    }
?>
