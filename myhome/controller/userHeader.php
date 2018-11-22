<?php

//判断是否登录部分：
header('Content-type:text/html; charset=utf-8');
// 开启Session，存储cookie
session_start();
// 首先判断Cookie是否有记住了用户信息
if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
    # 若记住了用户信息,则直接传给Session
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['email'] = $_COOKIE['email'];
    $_SESSION['u_id'] = $_COOKIE['u_id'];
    $_SESSION['islogin'] = 1;
}
if (!isset($_SESSION['islogin']))header("refresh:3;url=./login.html");

 ?>