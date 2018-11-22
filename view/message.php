<?php

/**
 * 留言板
 */

header('Content-type:text/html; charset=utf-8');

include('../control/querymessage.php');

include_once "../control/userHeader.php";
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

    <link rel="icon" type="image/png" href="../common/lib/lib/assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="32x32" href="../common/lib/lib/assets/i/favicon.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="../common/lib/lib/assets/i/favicon.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/favicon.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="../common/lib/lib/assets/css/amazeui.min.css">
    <link rel="stylesheet" href="../common/lib/lib/assets/css/app.css">
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
        <li class="am-active" ><a href="./message.php">留言板</a></li>
        <li ><?php if (isset($_SESSION['islogin'])){
                echo "<a>欢迎您，{$_SESSION['username']}</a>";
            }else {
                echo " >|登录|</a>";
            } ?></li>
    </ul>
</div>




<div class="am-u-md-6 am-u-md-centered" style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px"  >
    <form action="../control/messagesave.php" method="post" class="am-form am-form-horizontal">


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

        </div>
        <div class="am-form-group">
            <div class="am-u-md-3">
                <img src="../myModel/image_captcha.php" onclick="this.src='../control/getImage.php?'+new Date().getTime();" width="100" height="30">
            </div>
            <div class="am-u-md-3">
                <input type="text" name="captcha" placeholder="请输入验证码"><br/>
            </div>
            <div class="am-u-md-6">
                <button type="submit" name="commit" class="am-btn am-btn-primary" >提交</button>
            </div>
        </div>
        <div class="am-form-group"></div>
    </form action="../control/messagesave.php">
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
    <script src="../common/lib/lib/assets/js/amazeui.ie8polyfill.min.js"></script>
    <![endif]-->
<script src="../common/lib/lib/assets/js/amazeui.min.js"></script>
</body>
</html>