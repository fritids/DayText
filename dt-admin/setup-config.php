<?php
    define('ABSPATH', dirname(__FILE__) . '/' );
    function printErrorPage(){
        $file_handle = fopen(ABSPATH."error_page.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
    }
    $step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;
    if($step == 0){
        $file_handle = fopen(ABSPATH."setup-config.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
    }elseif ($step == 1){
        $file_handle = fopen(ABSPATH."step1.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
    }elseif ($step == 2){
        foreach (array( 'dbname', 'uname', 'pwd', 'dbhost', 'prefix' ) as $key){
            $$key = trim($_POST[$key]);
        }
        $tryagain_link = '</p><p class="step"><a href="setup-config.php?step=1" onclick="javascript:history.go(-1);return false;" class="button button-large">' . '再试一次'  . '</a>';
        if ( empty( $prefix ) ){
            printErrorPage();
            die('<strong>ERROR</strong>: "Table Prefix" must not be empty.' . $tryagain_link);
        }
		if (preg_match( '|[^a-z0-9_]|i', $prefix )){
            printErrorPage();
            die('<strong>ERROR</strong>: "Table Prefix" can only contain numbers, letters, and underscores.' . $tryagain_link );
        }
        
        define('DB_NAME', $dbname);
        define('DB_USER', $uname);
        define('DB_PASSWORD', $pwd);
        define('DB_HOST', $dbhost);
        
        $link = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        if (!$link){
            printErrorPage();
            die('<h1>数据库连接错误</h1><p>您在<code>dt-config.php</code>文件中提供的数据库用户名和密码可能不正确，或者无法连接到<code>'.DB_HOST.'</code>上的数据库服务器，这意味着您的主机数据库服务器已停止工作。</p><ul><li>您确认您提供的用户名和密码正确么？</li><li>您确认您提供的主机名正确么？</li><li>您确认数据库服务器运行正常么？</li></ul><p>若您不理解上述术语，请联系您的服务提供商。如果您仍需帮助，可访问 <a href=\'#\'>DayText</a>，或<a href=\'http://wordpress.org/support/\'>DayText支持论坛</a>（英文）。' .$tryagain_link);
        }
        
        mysql_close($link);
        
        $path = substr(ABSPATH,0,strlen(ABSPATH) - 9);
        $content = "<?php\n/**\n  * DayText基础配置文件。\n  *\n  * 本文件包含以下配置选项：MySQL设置、数据库表名前缀\n  *\n  * 这个文件被安装程序用于自动生成dt-config.php配置文件，\n  * 您可以手动复制这个文件，并重命名为“dt-config.php”，然后填入相关信息。\n  *\n  * @package DayText\n  */\n  \n// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //\n/** DayText数据库的名称 */\ndefine('DB_NAME', '".DB_NAME."');\n  \n/** MySQL数据库用户名 */\ndefine('DB_USER', '".DB_USER."');\n\n/** MySQL数据库密码 */\ndefine('DB_PASSWORD', '".DB_PASSWORD."');\n\n/** MySQL主机 */\ndefine('DB_HOST', '".DB_HOST."');\n\n/** 创建数据表时默认的文字编码 */\ndefine('DB_CHARSET', 'utf8');\n\n/** 数据库整理类型。如不确定请勿更改 */\ndefine('DB_COLLATE', '');\n\n/**\n  * DayText数据表前缀。\n  *\n  * 如果您有在同一数据库内安装多个DayText的需求，请为每个DayText设置\n  * 不同的数据表前缀。前缀名只能为数字、字母加下划线。\n  */\n\$table_prefix  = '".$prefix."';\n\n/** DayText目录的绝对路径。 */\nif ( !defined('ABSPATH') )\ndefine('ABSPATH', dirname(__FILE__) . '/');\n\n/** 设置DayText变量和包含文件。 */\nrequire_once(ABSPATH . 'dt-settings.php');\n?>";
        if ( ! is_writable($path) ){
            printErrorPage();
            die('抱歉，但是向导在您的文件系统中没有足够的权限写入<code>dt-config.php</code>文件。<br />请手动创建<code>dt-config.php</code>文件，并拷入如下文本，之后保存。</p><textarea id="dt-config" cols="98" rows="15" class="code" readonly="readonly">'.$content.'</textarea><p>完成之后，请点击&#8220;进行安装&#8221;。</p><p class="step"><a href="install.php" class="button button-large">进行安装</a></p><script>(function(){var el=document.getElementById(\'dt-config\');el.focus();el.select();})();</script>');
        }else{
            $path .= "dt-config.php";
            $fp = fopen($path,"wb");
            fwrite($fp,$content);
            chmod($fp,0777);
            header("location: install.php");
        }
    }
?>