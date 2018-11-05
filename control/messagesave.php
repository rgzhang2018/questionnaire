<?php
header('Content-type:text/html; charset=utf-8');


if(!isset($_POST['commit'])){
    header("refresh:3;url=../view/message.php");
    die("错误提交！三秒后返回</br> ");
}
session_start();

$time = time();
$text = $_POST['text1'];
$name = $_POST['text2'];
$captcha = $_POST["captcha"];
//2. 将session中的验证码和用户提交的验证码进行核对,当成功时提示验证码正确，并销毁之前的session值,不成功则重新提交
if(strtolower($_SESSION["captcha"]) == strtolower($captcha)){
    echo "验证码正确!";
    $_SESSION["captcha"] = "";
}else{
    header("refresh:3;url=../view/message.php");
    die("验证码错误！三秒后返回</br> ");
}


function isOk( $tempText ){
	if($tempText == '')return false;
	$s = ['av','AV','gcd'];
	foreach ($s as $key => $value){
		if($value === $tempText)return false;

	}
	return true;
}

if(isOk($text) ){
	echo "留言内容：";
	echo $text;
	echo "</br>";
}else {
    header("refresh:3;url=../view/message.php");
	die("内容不能为空！</br> 请重新输入");
}

if(isOk($name) ){
	echo "留言人：";
	echo $name;
	echo "</br>";
}else{
    header("refresh:3;url=../view/message.php");
	die("留言人不能为空！</br> 请重新输入");
}



//插入数据库中：
include '../DB/quicksql.php';
$u_id = 1;
if(isset($_SESSION["u_id"]))$u_id=$_SESSION["u_id"];
$insertMessage = "INSERT INTO messageboard (u_id,m_message,m_name,m_time) VALUES ('{$u_id}','{$text}','{$name}','{$time}');";


$flag = $db1->query($insertMessage);

//错误判断
if($flag){
    echo "insert OK!";
    print('<br>留言成功！<br>三秒后自动跳转....');
}else {
	echo "insert Error :</br>";
	var_dump($flag);
}
header("refresh:3;url=../view/message.php");

