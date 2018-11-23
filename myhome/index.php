<?php
/**
 * 首页
 */


//判断是否登录部分：
header('Content-type:text/html; charset=utf-8');
// 开启Session，存储cookie
session_start();

// 首先判断Cookie是否有记住了用户信息
if (isset($_COOKIE['username']) && isset($_COOKIE['email'])) {
    # 若记住了用户信息,则直接传给Session
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['email'] = $_COOKIE['email'];
    $_SESSION['islogin'] = 1;
}
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
    <title>我的问卷系统V1.0</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="public_html/res/home/default/assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="32x32" href="public_html/res/home/default/assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="public_html/res/home/default/assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="public_html/res/home/default/assets/css/amazeui.min.css">
    <link rel="stylesheet" href="public_html/res/home/default/assets/css/app.css">
</head>
<body  style="background-color: #e9e9e9">

<div class="am-g">
    <br>
    <div class="am-u-sm-1"></div>
    <div class="am-u-sm-4 am-fr"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
    <br>
    <br>
</div>

<div class="am-animation-scale-up  am-u-sm-4 am-u-sm-centered" >
    <ul class="am-nav am-nav-tabs">
        <li class="am-active"><a href="./index.php">首页</a></li>
        <li ><a href="home/view/admin_index.php">控制台</a></li>
        <li ><a href="home/view/message.php">留言板</a></li>
        <li ><?php if (isset($_SESSION['islogin'])){
                    echo "<a>欢迎您，{$_SESSION['username']}</a>";
                }else {
                    echo " >|登录|</a>";
                } ?></li>
    </ul>
</div>

<div class="am-u-md-4 am-u-md-centered" style="background-color: #ffffff ;box-shadow: 10px 10px 5px"  >

    <form  action="#" method="post" class="am-form am-form-horizontal">
        <div class="am-form-group" style="text-align:center">
            <br>
            <?php
            if (isset($_SESSION['islogin'])) {
                // 若已经登录
                echo "你好! ".$_SESSION['username'].' ,欢迎您来到主页 <a href="../control/logout.php" >|点击注销|</a><br>';
            } else {
                // 若没有登录
                echo "欢迎您来到主页，<a href='home/view/login.html'>点击登录/注册</a>";
            }
            ?>
            <hr>
        </div>

        <div class="am-form-group">
            <a class="am-btn am-u-sm-centered am-btn-primary" href="home/view/message.php" >
                留言板
            </a>
        </div>
        <div class="am-form-group"></div>
        <div class="am-form-group">
            <a class="am-btn am-u-sm-centered am-btn-primary" href="../home/view/admin_index.php">
                发布问卷
            </a>
        </div>
        <div class="am-form-group"></div>
        <div class="am-form-group">
            <a class="am-btn am-u-sm-centered am-btn-primary" href="home/view/get_q.php">
                填写问卷
            </a>
        </div>
        <div class="am-form-group"></div>
        <div class="am-form-group">
            <a class="am-btn am-u-sm-centered am-btn-primary" href="http://www.rgzhang.top">
                个人主页
            </a>
        </div>

        <div class="am-form-group">
            <hr><br>
        </div>
    </form>
</div>





<!--[if (gte IE 9)|!(IE)]><!-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="public_html/res/home/default/assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="public_html/res/home/default/assets/js/amazeui.min.js"></script>
</body>
</html>