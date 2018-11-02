<?php

header('Content-type:text/html; charset=utf-8');
include('../DB/quicksql.php');
$queryMessage = "SELECT * FROM messageboard;";

$mysql_result = $db1->query($queryMessage);

if($mysql_result == false)echo "SQL语句错误!";

$arrs = [] ;
while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
    $arrs [$row['id']] = $row;
}

//判断是否登录部分：
header('Content-type:text/html; charset=utf-8');
// 开启Session，存储cookie
session_start();

// 首先判断Cookie是否有记住了用户信息
if (isset($_COOKIE['username']) && isset($_COOKIE['email'])) {
    # 若记住了用户信息,则直接传给Session
    echo "1111";
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['email'] = $_COOKIE['email'];
    $_SESSION['islogin'] = 1;
}

?>

<!doctype html>
<html class="no-js" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>填写问卷</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="../assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="32x32" href="../assets/i/favicon.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="../assets/i/favicon.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/favicon.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="../assets/css/amazeui.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
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
        <li ><a href="./view/admin_index.php">控制台</a></li>
        <li ><a href="view/message.php">留言板</a></li>
        <li ><?php if (isset($_SESSION['islogin'])){
                echo "<a>欢迎您，{$_SESSION['username']}</a>";
            }else {
                echo "<a href=\"./view/login.php\" >|登录|</a>";
            } ?></li>
    </ul>
</div>




<div class="am-u-md-6 am-u-md-centered" style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px"  >
    <form action="../control/messagesave.php" method="post" class="am-form am-form-horizontal">


        <div class="am-form-group">
            <h1>这里是问卷标题</h1>
        </div>

        <div class="am-form-group">
            <h4>问卷描述</h4>
        </div>

        <br>
        <hr>

        <div class="am-form-group">
            <h3>这里是单选的问题 <sup class="am-text-danger">*</sup></h3>
            <label class="am-radio">
                <input type="radio" name="radio10" value="male" data-am-ucheck required> 选项一
            </label>
            <label class="am-radio">
                <input type="radio" name="radio10" value="female" data-am-ucheck> 选项二
            </label>
            <label class="am-radio">
                <input type="radio" name="radio10" value="pig" data-am-ucheck> 选项三
            </label>
            </div>


        <br>
        <hr>

        <div class="am-form-group">
            <h3>多选题</h3>
            <label class="am-checkbox">
                <input type="checkbox" value="" data-am-ucheck> 没有选中
            </label>
            <label class="am-checkbox">
                <input type="checkbox" checked="checked" value="" data-am-ucheck checked>
                已选中
            </label>
            <label class="am-checkbox">
                <input type="checkbox" value="" data-am-ucheck disabled>
                禁用/未选中
            </label>
            <label class="am-checkbox">
                <input type="checkbox" checked="checked" value="" data-am-ucheck disabled
                       checked>
                禁用/已选中
            </label>
        </div>

        <br>
        <hr>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="col-sm-2 am-form-label">这里是问答题题目</label>
            <div class="col-sm-10">
                <textarea id="doc-ta-1" placeholder="80字以内" rows="4"></textarea>
            </div>
        </div>

        <br>
        <hr>


        <div class="am-form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="am-btn am-btn-primary" >提交</button>
            </div>
        </div>
        <div class="am-form-group"></div>
    </form action="../control/votesave.php">
</div>




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