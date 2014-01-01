<?php
    if ( false ) {
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Error: PHP未运行</title>
</head>
<body class="dt-core-ui">
<h1 id="logo"><a href="#">DayText</a></h1>
<h2>Error: PHP未运行</h2>
<p>DayText要求您的服务器运行PHP, 然而, 当你看到这个页面时就表明服务器上的PHP程序没有安装或者被关掉了。</p>
</body>
</html>
<?php
    }
    $step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;
    define('ABSPATH1', dirname(__FILE__) . '/' );
    $tryagain_link = '</p><p class="step"><a href="setup-config.php?step=1" onclick="javascript:history.go(-1);return false;" class="button button-large">' . '再试一次'  . '</a>';
    function printErrorPage(){
        $file_handle = fopen(ABSPATH1."error_page.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
    }
    
    if (!file_exists('../dt-config.php') ) {
        printErrorPage();
        die('似乎<code>dt-config.php</code>文件不存在。DayText需要这个文件方可正常工作。</p><p>需要帮助？<a href=\'#\'>没问题！</a></p><p>您可以通过我们提供的web向导来创建<code>dt-config.php</code>文件，但并非所有服务器都支持我们的配置向导。最安全、传统的办法是手动创建该文件。'.$tryagain_link);
    }
    function printPage(){
        $file_handle = fopen(ABSPATH1."install.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
    }
    function is_email($email) {
        
        // Test for the minimum length the email can be
        if ( strlen( $email ) < 3 ) {
            return false;
        }
        
        // Test for an @ character after the first position
        if ( strpos( $email, '@', 1 ) === false ) {
            return false;
        }
        
        // Split out the local and domain parts
        list( $local, $domain ) = explode( '@', $email, 2 );
        
        // LOCAL PART
        // Test for invalid characters
        if ( !preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
            /** This filter is documented in wp-includes/formatting.php */
            return false;
        }
        
        // DOMAIN PART
        // Test for sequences of periods
        if ( preg_match( '/\.{2,}/', $domain ) ) {
            /** This filter is documented in wp-includes/formatting.php */
            return false;
        }
        
        // Test for leading and trailing periods and whitespace
        if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) {
            /** This filter is documented in wp-includes/formatting.php */
            return false;
        }
        return true;
    }
    function display_header() {
        header( 'Content-Type: text/html; charset=utf-8' );
    }
    function display_message($message) {
        $file_handle = fopen(ABSPATH1."install_error_1.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
        echo $message;
        $file_handle = fopen(ABSPATH1."install_error_2.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
        die("");
    }
    if($step == 0){
        if(file_exists(ABSPATH1."dt-admin.xml")){
            $file_handle = fopen(ABSPATH1."install_already_succeed.html", "r");
            while (!feof($file_handle)) {
                $line = fgets($file_handle);
                echo $line;
            }
            fclose($file_handle);
            exit;
        }
        display_header();
        printPage();
    }elseif($step == 2){
        if(file_exists(ABSPATH1."dt-admin.xml")){
            $file_handle = fopen(ABSPATH1."install_already_succeed.html", "r");
            while (!feof($file_handle)) {
                $line = fgets($file_handle);
                echo $line;
            }
            fclose($file_handle);
            exit;
        }
        foreach (array( 'weblog_title', 'user_name', 'admin_password', 'admin_password2', 'admin_email','blog_public' ) as $key){
            $$key = trim($_POST[$key]);
        }
        
        
        if(empty($user_name)){
            display_message("请提供有效的用户名。");
        }elseif(empty($admin_email)){
            display_message("抱歉，电子邮件地址无效。形如username@example.com的才是电子邮件地址。");
        }elseif($admin_password != $admin_password2 && strlen($admin_password)>0){
            display_message("您两次输入的密码不符，请重试。");
        }elseif(!is_email($admin_email)){
            display_message("对不起, <code>".$admin_email."</code>不是一个有效的Email地址. Email看上去应该像 <code>username@example.com</code>");
        }
        
        $data = simplexml_load_file(ABSPATH1."dt-admin-sample.xml");
        $data->root['username'] = $user_name;
        $data->root['password'] = $admin_password;
        $data->root['email'] = $admin_email;
        $data->site['title'] = $weblog_title;
        $data->site['anyone'] = $blog_public;
        $data->site['installed'] = "YES";
        $newxml = $data->asXML();
        $fp1 = fopen("dt-admin.xml", "wb");
        fwrite($fp1, $newxml);
        fclose($fp1);

        
        $file_handle = fopen(ABSPATH1."install_succeed.html", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
        
        require_once(ABSPATH1.'smtp.php');
        $smtpserver = "smtp.qq.com";
        $smtpserverport = 25;
        $smtpusermail = "445108920@qq.com";
        $smtpemailto = $admin_email;
        $smtpuser = "445108920";
        $smtppass = "xrmdeqq1234clcl!";
        $mailsubject = "您的DayText站点\"".$weblog_title."\"已经安装成功";
        $mailbody = "<p>恭喜, 您的DayText站点\"".$weblog_title."\"已经安装妥当!</p><p>请用下面的帐号密码登录管理员帐户: <br />用户名: ".$user_name."</p><p>&nbsp;&nbsp;&nbsp;密码: ".$admin_password."</p><p>2013 DayText</p>";
        $mailtype = "HTML";
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
        $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
    }
?>