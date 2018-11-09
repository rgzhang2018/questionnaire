<?php

/**
 * 注册控制页面
 * 接受从../view/register传来的注册信息
 */

header('Content-type:text/html; charset=utf-8');
if (isset($_POST['register'])) {
    # 接收用户的登录信息

    $email = $_POST['email'];
    $password = $_POST['password1'];
    $name = $_POST['name'];

    include '../DB/quicksql.php';

    $sql = "INSERT INTO user (u_email,u_password,u_name) VALUES ('{$email}','{$password}','{$name}');";
    $mysql_result = $db1->query($sql);
    if(!$mysql_result){
        header('refresh:3; url=../view/register.php');
        echo "注册错误！三秒后返回注册页面";
        exit;
    }else{
        header('refresh:3; url=../view/login.php');
        echo "注册成功！三秒后跳转到登录页面";
        exit;
    }
}

