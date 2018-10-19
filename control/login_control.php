<?php
header('Content-type:text/html; charset=utf-8');
// 开启Session，存储cookie
session_start();
//连接数据库


// 处理用户登录信息
if (isset($_POST['login'])) {
    # 接收用户的登录信息

    $email = $_POST['email'];
    $password = $_POST['password'];
    if ( !isset($email) ||  !isset($password)) {
        // 若为空,提示错误,并3秒后返回登录界面
        header('refresh:3; url=../view/login.php');
        echo "用户名或密码不能为空,系统将在3秒后跳转到登录界面,请重新填写登录信息!";
        exit;
    }

    //连接sql数据库，然后查询对应的信息
    include '../DB/quicksql.php';
    $sql = "SELECT * FROM q_user WHERE q_email LIKE '{$email}';";
    $mysql_result = $db1->query($sql);

    //错误判断
    if(!$mysql_result){
        //查询错误 证明没有该用户
        header('refresh:3; url=../view/login.php');
        echo "用户名或密码错误,系统将在3秒后跳转到登录界面,请重新填写登录信息!";
        exit;
    }

    $message = $mysql_result->fetch_array( MYSQLI_ASSOC );

    // 判断提交的登录信息
    if (($email != $message['q_email']) || ($password != $message['q_password'])) {
        # 用户名或密码错误,同空的处理方式
        header('refresh:3; url=../view/login.php');
        echo "用户名或密码错误,系统将在3秒后跳转到登录界面,请重新填写登录信息!";
        exit;
    } else{
        # 用户名和密码都正确,将用户信息存到Session中
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $message['q_name'];
        $_SESSION['islogin'] = 1;
        echo "您好！{$message['q_name']}，登陆成功！";
        // 若勾选7天内自动登录,则将其保存到Cookie并设置保留7天
        if ($_POST['remember'] == "yes"){
            setcookie('email', $email, time()+7*24*60*60);
            setcookie('username', $message['q_name'], time()+7*24*60*60);
            setcookie('code', md5($email.md5($password)), time()+7*24*60*60);
        } else {
            // 没有勾选则删除Cookie
            setcookie('email', '', time()-999);
            setcookie('username', '', time()-999);
            setcookie('code', '', time()-999);
        }
        // 处理完附加项后跳转到登录成功的首页
        header('refresh:3; url=../view/admin_index.php');
        echo "三秒后转跳个人页面...";
        exit;
    }
}elseif (isset($_POST['register']) ){
    header('location:../view/register.php');
}
?>
