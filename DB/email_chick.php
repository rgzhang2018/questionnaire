<?php
//插入的数据库：
$host001 = '127.0.0.1';
$username001 = 'root';
$password001 = 'a530371306';
$dataBase001 = 'schema1';

$db1 = new mysqli($host001,$username001,$password001,$dataBase001);

////错误判断
//if($db1->connect_errno <> 0 ){
//    echo "connect fail!";
//}

//设置传输过去的编码格式为utf-8（注意，没有'-'）
$db1->query("SET NAMES UTF8");

if(isset($_GET['email'])){
    sleep(1);
    $email = $_GET['email'];
    $sql="SELECT * FROM q_user WHERE q_email LIKE '{$email}';";
    $mysql_result = $db1->query($sql);
    $row = $mysql_result->fetch_array( MYSQLI_ASSOC );

    if(isset($row)){
        echo "<font color='red'>用户名已存在</font>";
    }else{
        echo "用户名可以使用";
    }
}



