<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Hello Amaze UI</title>

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
<body  style="background-color: #e9e9e9">

<div class="am-g">
    <div class="am-u-sm-1"></div>
    <div class="am-u-sm-2 am-u-sm-offset-9"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
</div>

<div class="am-animation-scale-up am-u-sm-3 am-u-sm-centered" >
    <ol class="am-breadcrumb">
        <li><a href="admin_index.php">首页</a></li>
        <li><a href="#">分类</a></li>
        <li class="am-active">内容</li>
    </ol>
</div>



<!--  here  -->
<div class="am-u-md-4 am-u-md-centered" style="background-color: #ffffff"  >

    <form  action="#" method="post" class="am-form am-form-horizontal">
        <div class="am-form-group" style="text-align:center">
            <br>
            <?php
	header('Content-type:text/html; charset=utf-8');
	// 开启Session
	session_start();

	// 首先判断Cookie是否有记住了用户信息
	if (isset($_COOKIE['username']) && isset($_COOKIE['email'])) {
		# 若记住了用户信息,则直接传给Session
        echo "1111";
		$_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['email'] = $_COOKIE['email'];
		$_SESSION['islogin'] = 1;
	}
	if (isset($_SESSION['islogin'])) {
		// 若已经登录
		echo "你好! ".$_SESSION['username'].' ,欢迎来到个人中心!<br>';
		echo "<a href='../control/logout.php'>注销</a>";
	} else {
		// 若没有登录
        header("refresh:3;url=./login.php");
		echo "您还没有登录！三秒后转跳到<a href='./login.php'>登录</a>界面";
	}
 ?>
            <hr>
        </div>

        <div class="am-form-group">
            <button type="button" class="am-u-sm-12 am-btn am-btn-primary  am-round">发布问卷</button>
        </div>
        <div class="am-form-group"></div>
        <div class="am-form-group">
            <button type="button" class="am-u-sm-12 am-btn am-btn-primary  am-round">我的问卷</button>
        </div>
        <div class="am-form-group"></div>
        <div class="am-form-group">
                <button type="button" class="am-u-sm-12 am-btn am-btn-primary  am-round">退出登录</button>
        </div>

        <div class="am-form-group">
            <hr><br>
        </div>


    </form>
</div>





<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="../assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="../assets/js/amazeui.min.js"></script>
</body>
</html>