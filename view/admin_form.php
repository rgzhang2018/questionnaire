<?php

//判断是否登录部分：
header('Content-type:text/html; charset=utf-8');
// 开启Session，存储cookie
session_start();
include_once "../class/reader.php";

// 首先判断Cookie是否有记住了用户信息
if (isset($_COOKIE['username']) && isset($_COOKIE['email'])) {
    # 若记住了用户信息,则直接传给Session
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['email'] = $_COOKIE['email'];
    $_SESSION['u_id'] = $_COOKIE['u_id'];
    $_SESSION['islogin'] = 1;
}
if(isset($_SESSION['u_id'])){
    $reader = new reader($_SESSION['u_id']);
    $questions = $reader->queryQuestions();

}
else{
    header("refresh:3;url=./login.php");
    echo "您还没有登录！三秒后转跳到<a href='./login.php'>登录</a>界面";
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
    <title>我的问卷</title>

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
    <br>
    <div class="am-u-sm-1"></div>
    <div class="am-u-sm-4 am-fr"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
    <br>
    <br>
</div>

<div class="am-animation-scale-up  am-u-sm-5 am-u-sm-centered" >
    <ul class="am-nav am-nav-tabs">
        <li ><a href="../index.php">首页</a></li>
        <li ><a href="./admin_index.php">控制台</a></li>
        <li ><a href="./message.php">留言板</a></li>
        <li ><?php if (isset($_SESSION['islogin'])){
                echo "<a>您好，{$_SESSION['username']}</a>";
            }else {
                echo "<a href=\"./login.php\" >|登录|</a>";
            } ?></li>
    </ul>
</div>





<!--  here  -->
<div class="am-u-md-6 am-u-md-centered" style="background-color: #ffffff ;box-shadow: 5px 5px 3px"   >

    <form  action="./admin_preview.php" method="post" class="am-form am-form-horizontal">
        <div class="am-form-group" style="text-align:center">
            <br>
            <?php
            if (isset($_SESSION['islogin'])) {
//                // 若已经登录
//                echo "你好! ".$_SESSION['username'].'!，';
//                echo "<a href='../control/logout.php'>注销</a>";
            } else {
                // 若没有登录
                header("refresh:3;url=./login.php");
                echo "您还没有登录！三秒后转跳到<a href='./login.php'>登录</a>界面";
            }
            ?>
            <hr>
        </div>

        <div class="am-form-group" style="text-align:center">
            <h2>我的问卷</h2>
        </div>
        <div class="am-form-group">
            <table class="am-table am-table-bordered am-table-radius am-table-striped">
                <thead>
                <tr>
                    <th>问卷名</th>
                    <th>描述</th>
                    <th>发布时间</th>
                    <th>问卷状态</th>
                </tr>
                </thead>
                <tbody>

                <?php
                    foreach ($questions as $question){
                        echo "<tr>";
                        echo "<td>{$question['q_name']}</td>";
                        echo "<td>{$question['q_describe']}</td>";
                        $time = date("Y-m-d H:m:s",$question['q_starttime']);
                        echo "<td>{$time}</td>";
                        echo "<td><button type=\"submit\" name=\"chick\" class=\"am-btn am-btn-default am-btn-block\">点击查看</button></td>";
                        echo "</tr>";
                        echo "<input type=\"hidden\" name=\"q_id\" value={$question['q_id']}>"; //设置隐藏提交的q_id
                    }
                ?>
                </tbody>
            </table>
        </div>


        <div class="am-form-group">

        </div>
    </form>
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

