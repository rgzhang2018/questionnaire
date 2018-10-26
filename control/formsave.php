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
$host = '127.0.0.1';
$username = 'root';
$password = 'a530371306';
$dataBase = 'schema1';

$db1 = new mysqli($host,$username,$password,$dataBase);

//错误判断
if($db1->connect_errno <> 0 ){
	echo "connect fail!</br>";
	echo $db1->connect_error;
}

//设置传输过去的编码格式为utf-8（注意，没有'-'）
$db1->query("SET NAMES UTF8");

$insertMessage = "INSERT INTO webmessage (message,name,time) VALUES ('{$text}','{$name}','{$time}');";


$flag = $db1->query($insertMessage);

//错误判断
if($flag)echo "insert OK!";
else {
	echo "insert Error :</br>";
	var_dump($flag);
}

header("refresh:3;url=../view/message.php");
print('<br>留言成功！<br>三秒后自动跳转....');
