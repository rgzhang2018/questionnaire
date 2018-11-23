<?php

/**
 * 问卷预览界面，需要指明问卷ID
 */

header('Content-type:text/html; charset=utf-8');
$q_id = 0;
if (isset($_POST['check'])){
    $q_id = $_POST['q_id'];
}else {
    header('refresh:3; url=./admin_form.php');
    die ("错误：未指明问卷，三秒后返回");
}


//判断是否登录部分：
include_once "../controller/userHeader.php";

//问卷信息部分
include "../model/reader.php";
$thisq = new reader($_SESSION['u_id']);
$questions = $thisq->getQuestionnaireByID($q_id);
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
    <title>问卷预览</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="../../assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="32x32" href="../../assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="../../assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="../../assets/css/amazeui.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">
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
        <li ><a href="../../index.php">首页</a></li>
        <li ><a href="./admin_index.php">控制台</a></li>
        <li ><a href="message.php">留言板</a></li>
        <li ><?php if (isset($_SESSION['islogin'])){
                echo "<a>您好，{$_SESSION['username']}</a>";
            }else {
                echo " >|登录|</a>";
            } ?></li>
    </ul>
</div>




<!--  here  -->
<div class="am-u-md-7 am-u-md-centered" style="background-color: #ffffff ;box-shadow: 5px 5px 3px"   >

    <form  action="#" method="post" class="am-form am-form-horizontal">
        <div class="am-form-group" style="text-align:center">
            <br>
            <?php
            if (isset($_SESSION['islogin'])) {
//                // 若已经登录
//                echo "你好! ".$_SESSION['username'].'!，';
//                echo "<a href='../control/logout.php'>注销</a>";
            } else {
                // 若没有登录
                header("refresh:3;url=./login.html");
                echo "您还没有登录！三秒后转跳到<a href='login.html'>登录</a>界面";
            }
            ?>

        </div>
        <div>问卷预览:</div>
        <div class="am-form-group" style="text-align:center">
            <hr>
            <h2><?php  echo "{$questions["questionnaire"]["q_name"]}"  ?></h2>
            <?php  echo "{$questions["questionnaire"]["q_describe"]}"  ?>

        </div>
        <hr>
        <div class="am-form-group">
<!--            这里是问卷内容-->
            <?php
            $count = 1;
            $type=0;      //表示单选、多选、问答,分别是0,1,2
            foreach ($questions as $question) {
                if(isset($question["q_id"])){
                    continue;
                }
            ?>
            <section class="am-panel am-panel-default">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">
                        <?php
                        echo "第{$count}题. {$question["question"]["qq_name"]}:";
                        $type = $question["question"]["qq_type"];
                        $count++;
                        ?>
                    </h3>

                </header>
                <div class="am-panel-bd">
                    <?php
                    if($type == 0){
                        echo "<div class=\"am-radio\">";
                        foreach ($question as $item){
                            if(isset($item["q_id"]))continue;
                            $item["qs_order"]++;
                            echo "<label><input type=\"radio\" name=\"{$item["qq_id"]}\" value=\"{$item["qs_order"]}\">{$item["qs_order"]}.{$item["qs_name"]}</label><br>";
                        }
                        echo "</div>";
                    }elseif($type==1){
                        echo "<div class=\"am-checkbox\">";
                        foreach ($question as $item){
                            if(isset($item["q_id"]))continue;
                            $item["qs_order"]++;
                            echo "<label><input type=\"checkbox\">{$item["qs_order"]}.{$item["qs_name"]}</label><br>";
                        }
                        echo "</div>";
                    } else{
                        echo " <textarea  placeholder=\"随便说点啥吧\" rows=\"5\" name=\"text1\" ></textarea>";
                    }
                     ?>
                </div>
            </section>
            <br>
            <?php } ?>
        </div>
    </form>
</div>





<!--[if (gte IE 9)|!(IE)]><!-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="../../assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="../../assets/js/amazeui.min.js"></script>
</body>
</html>

