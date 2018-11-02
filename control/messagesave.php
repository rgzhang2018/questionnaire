<?php
header('Content-type:text/html; charset=utf-8');
$text = $_POST['text1'];
$name = $_POST['text2'];
$time = time();


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
	die("内容不能为空！</br> 请重新输入");
}

if(isOk($name) ){
	echo "留言人：";
	echo $name;
	echo "</br>";
}else{
	die("留言人不能为空！</br> 请重新输入");
}



//插入数据库中：
include '../DB/quicksql.php';

$insertMessage = "INSERT INTO messageboard (m_message,m_name,m_time) VALUES ('{$text}','{$name}','{$time}');";


$flag = $db1->query($insertMessage);

//错误判断
if($flag){
    echo "insert OK!";
    print('<br>留言成功！<br>三秒后自动跳转....');
}
else {
	echo "insert Error :</br>";
	var_dump($flag);
}
header("refresh:3;url=../view/message.php");

