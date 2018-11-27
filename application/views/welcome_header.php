<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 15:54
 */

defined('BASEPATH') OR exit('No direct script access allowed');
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
    <title><?php echo "{$title}"?></title>

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

<body style="background-color: #e9e9e9">

<header class="am-topbar am-topbar-inverse">
    <h1 class="am-topbar-brand">
        <a href="#">简易问卷系统</a>
    </h1>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            <li <?php if(isset($pageFlag) && $pageFlag===0) echo "class=\"am-active\""; ?>><a href="./index.php/VisitorView/index">首页</a></li>
            <li <?php if(isset($pageFlag) && $pageFlag===1) echo "class=\"am-active\""; ?>><a href="./index.php/UserView/adminIndex">个人中心</a></li>
            <li><a href="./index.php/VisitorView/getQuestionID">填写问卷</a></li>
            <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
                    下拉 <span class="am-icon-caret-down"></span>
                </a>
                <ul class="am-dropdown-content">
                    <li class="am-dropdown-header">标题</li>
                    <li><a href="#">1. 去月球</a></li>
                    <li class="am-active"><a href="#">2. 去火星</a></li>
                    <li><a href="#">3. 还是回地球</a></li>
                    <li class="am-disabled"><a href="#">4. 下地狱</a></li>
                    <li class="am-divider"></li>
                    <li><a href="#">5. 桥头一回首</a></li>
                </ul>
            </li>
        </ul>

        <form class="am-topbar-form am-topbar-left am-form-inline" role="search">
            <div class="am-form-group">
                <input type="text" class="am-form-field am-input-sm" placeholder="搜索">
            </div>
        </form>

        <div class="am-topbar-right">
            <div class="am-dropdown" data-am-dropdown="{boundary: '.am-topbar'}">
                <button class="am-btn am-btn-secondary am-topbar-btn am-btn-sm am-dropdown-toggle" data-am-dropdown-toggle>其他 <span class="am-icon-caret-down"></span></button>
                <ul class="am-dropdown-content">
                    <li>
                        <a href="https://github.com/rgzhang2018/questionnaire">
                            <i class="am-icon-github am-icon-fw am-u-sm-left "></i>GitHub
                        </a>
                    </li>
                    <li><a href="./index.php/VisitorView/register">点击注册</a></li>
                    <li><a href="./index.php/VisitorView/messageBoard">留言板</a></li>
                </ul>
            </div>
        </div>

        <div class="am-topbar-right">

            <a href="https://github.com/rgzhang2018/questionnaire">
                <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>GitHub</button>
            </a>
            <?php
            if(isset($_SESSION['is_login'])) {
                echo "你好! ".$_SESSION['username'].'!，';
                echo "<a href='./index.php/UserView/logout'>|注销|</a>";
            }else{
                echo "<a href='./index.php/VisitorView/login'>
                        <button class=\"am-btn am-btn-primary am-topbar-btn am-btn-sm\">点击登录</button>
                      </a>";
            }
            ?>

        </div>
    </div>
</header>