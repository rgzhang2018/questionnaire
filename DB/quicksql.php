<?php 
//插入的数据库：
$host001 = '127.0.0.1';
$username001 = 'root';
$password001 = 'a530371306';
$dataBase001 = 'schema1';

$db1 = new mysqli($host001,$username001,$password001,$dataBase001);

//错误判断
if($db1->connect_errno <> 0 ){
	echo "connect fail!</br>";
	echo $db1->connect_error;
}

//设置传输过去的编码格式为utf-8（注意，没有'-'）
$db1->query("SET NAMES UTF8");

// $insertMessage = "INSERT INTO webmessage (message,name,time) VALUES ('{$text}','{$name}','{$time}');";
// $flag = $db1->query($insertMessage);

// //错误判断
// if($flag)echo "insert OK!";
// else {
// 	echo "insert Error :</br>";
// 	var_dump($flag);
// }



 ?>