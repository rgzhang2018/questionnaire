
<?php
//include('../DB/quicksql.php');
//$queryMessage = "SELECT * FROM user ;";
//
//$mysql_result = $db1->query($queryMessage);
//
//if($mysql_result == false)echo "SQL语句错误!";
//
//$arrs = [] ;
//while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
//    $arrs [$row['u_id']] = $row;
//}

?>
<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>注册页面</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="../assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="32x32" href="../assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="../assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="../assets/css/amazeui.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body style="background-color: #e9e9e9">

<div class="am-g">
    <br>
    <div class="am-u-sm-5"><a href="../index.php"><i class="am-icon-home am-icon-fw am-u-sm-left am-fr"></i></a></div>
    <div class="am-u-sm-5 am-fr"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
    <br>
    <br>
</div>






<!--  here  -->

<div class="am-u-md-5 am-u-sm-centered"  style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px">
    <form class="am-form am-form-horizontal"  action="../control/register_control.php"  method="post">
        <div class="am-form-group">
            <br>
        </div>

        <div class="am-form-group" style="text-align:center">
            <h1>注册</h1>
            <hr>
        </div>

        <div class="am-form-group">
            <label for="reg-email" class="am-u-sm-2 am-form-label">邮件</label>
            <div class="am-u-sm-10">
                <input type="email" name="email" id="reg-email" placeholder="输入你的电子邮件" onblur="nameChick()">
            </div>
        </div>

        <div class="am-form-group">
            <label for="reg-pwd1" class="am-u-sm-2 am-form-label">密码</label>
            <div class="am-u-sm-10">
                <input type="password" name="password1" id="reg-pwd1" placeholder="请输入密码">
            </div>
        </div>
        <div class="am-form-group">
            <label for="reg-pwd2" class="am-u-sm-2 am-form-label" >确认</label>
            <div class="am-u-sm-10">
                <input type="password" name="password2" id="reg-pwd2" placeholder="请再次输入密码" onblur="validate()">
            </div>
        </div>
        <div class="am-form-group " style="text-align:center">
            <label id = "reg-msg" style="font-size: 1.4rem"></label>
        </div>
        <div class="am-form-group">
            <label for="reg-name" class="am-u-sm-2 am-form-label">昵称</label>
            <div class="am-u-sm-10">
                <input type="text" name="name" id="reg-name" placeholder="起个昵称吧">
            </div>
        </div>

        <div class="am-form-group">
            <br>
        </div>

        <div class="am-form-group">
            <div class="am-u-sm-8 am-u-sm-centered">
                <button type="submit" name="register" id="reg-submit" class="am-btn am-btn-success am-btn-block">点击注册</button>
            </div>
        </div>
        <div class="am-form-group">
            <hr>
            <br>
        </div>
    </form>
</div>

  <script>
    function validate() {
        var pwd1 = document.getElementById("reg-pwd1").value;
        var pwd2 = document.getElementById("reg-pwd2").value;
        <!-- 对比两次输入的密码 -->
        if(pwd1 === pwd2 ) {
            document.getElementById("reg-msg").innerHTML="<font color='green'>两次密码相同，OK!</font>";
            document.getElementById("reg-submit").disabled = false;
        }
        else {
            document.getElementById("reg-msg").innerHTML="<font color='red'>两次密码不相同</font>";
            document.getElementById("reg-submit").disabled = true;
        }
    }


    var xmlHttp;
    function S_xmlhttprequest(){
        if(window.ActiveXObject){
            xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
        }else if(window.XMLHttpRequest){
            xmlHttp = new XMLHttpRequest();
        }
    }
    function nameChick(){
        var f = document.getElementById("reg-email").value;//获取文本框内容
        S_xmlhttprequest();
        xmlHttp.open("GET","../DB/email_chick.php?email="+f,true);//找开请求
        xmlHttp.onreadystatechange = byphp;//准备就绪执行
        xmlHttp.send(null);//发送

    }
    function byphp(){
        //判断状态

        if(xmlHttp.readyState==1){//Ajax状态

            document.getElementById('reg-msg').innerHTML = "<font color='red'>正在加载</font>";
        }
        if(xmlHttp.readyState==4){//Ajax状态
            if(xmlHttp.status==200){//服务器端状态
                document.getElementById('reg-msg').innerHTML = xmlHttp.responseText;  //把内容传回
            }
        }
    }


</script>


<!--[if (gte IE 9)|!(IE)]><!-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="../assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="../assets/js/amazeui.min.js"></script>
</body>
</html>