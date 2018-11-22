<?php

/**
 * 登出模块。
 * 各个页面的导航条均有转跳链接
 */

header('Content-type:text/html; charset=utf-8');
// 注销后的操作
session_start();
// 清除Session
$username = $_SESSION['username'];  //用于后面的提示信息
$_SESSION = array();
session_destroy();

// 清除Cookie
setcookie('username', '', time()-99);
setcookie('code', '', time()-99);

// 提示信息
echo "欢迎下次光临, ".$username.'<br>';
echo "<a href='../view/login.html'>重新登录</a>";

?>
