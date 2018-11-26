<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 14:55
 */

defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="am-u-md-10" style="background-color: #dddddd">
    <br>

<header class="am-topbar ">
    <h1 class="am-topbar-brand">
        <a href="#">简易问卷系统</a>
    </h1>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
        </ul>

        <form class="am-topbar-form am-topbar-left am-form-inline" role="search">
            <div class="am-form-group">
                <input type="text" class="am-form-field am-input-sm" placeholder="输入搜索内容">
            </div>
        </form>

        <div class="am-topbar-right">
            <p class="am-topbar-brand"><?php  echo "您好! {$username}!";  ?></p>


            <a href="../visitorview/index">
                <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">回到首页</button>
            </a>


            <a href='../visitorview/logout'>
                    <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">点击注销</button>
            </a>
        </div>
    </div>
    </header>

