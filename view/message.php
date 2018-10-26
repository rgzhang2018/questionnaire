<?php

header('Content-type:text/html; charset=utf-8');

include('../DB/quicksql.php');
$queryMessage = "SELECT * FROM messageboard;";

$mysql_result = $db1->query($queryMessage);

if($mysql_result == false)echo "SQL语句错误!";

$arrs = [] ;
while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
 $arrs [$row['m_id']] = $row;
}

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
<html class="no-js" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>留言板</title>

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
    <div class="am-u-sm-3 am-u-sm-offset-8"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
    <br>
    <br>
</div>

<div class="am-animation-scale-up  am-u-sm-5 am-u-sm-centered" >
    <ul class="am-nav am-nav-tabs">
        <li ><a href="../index.php">首页</a></li>
        <li ><a href="#">控制台</a></li>
        <li class="am-active"><a href="message.php">留言板</a></li>
        <div class="am-fr">
            <?php if (isset($_SESSION['islogin'])){
                echo "欢迎您,{$_SESSION['username']} &nbsp;&nbsp;<a href=\"../control/logout.php\" >|注销|</a>";
            }else {
                echo "<a href=\"./login.php\" >|点击登录|</a>";
            } ?>
        </div>
    </ul>
</div>




<div class="am-u-md-6 am-u-md-centered" style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px"  >
    <form  action="../control/formsave.php"  method="post" class="am-form am-form-horizontal">


        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="col-sm-2 am-form-label">留言</label>
            <div class="col-sm-10">
                <textarea  placeholder="随便说点啥吧" rows="5" name="text1" ></textarea>
            </div>
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-3" class="col-sm-2 am-form-label" >昵称</label>
            <div class="col-sm-10">
                <input type="text" id="doc-ipt-3" placeholder="输入你的昵称" name="text2" >
            </div>
        </div>

        <div class="am-form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="am-btn am-btn-primary" >提交</button>
            </div>
        </div>
        <div class="am-form-group"></div>
    </form action="../control/formsave.php">
</div>



<div class="am-u-sm-12">
    <br>
</div>
<div class="am-u-md-6 am-u-md-centered"  style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px">

    <div class="am-u-sm-12"><h4>历史留言：</h4></div>
    <br>
    <br>
    <br>
    <?php
    foreach ($arrs as $value) {
        ?>
        <div>
            <section class="am-panel am-panel-default">
                <header class="am-panel-hd">
                    <span class="am-fr"><?php echo date("Y-m-d H:m:s",$value['m_time']); ?> </span>
                    <h3 class="am-panel-title"><?php echo "{$value['m_id']}楼.  {$value['m_name']}:"; ?></h3>

                </header>
                <div class="am-panel-bd">
                    <?php echo "{$value['m_message']}"; ?>
                </div>
            </section>
            <br>
        <?php
    }
    ?>
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