<?php

/**
 * 注册控制：检测用户名是否存在
 */


header('Content-type:text/html; charset=utf-8');
include '../myModel/quicksql.php';

if(isset($_GET['email']) && $_GET['email']!=""){
    $email = $_GET['email'];
    $sql="SELECT * FROM user WHERE u_email LIKE '{$email}';";
    $mysql_result = $db1->query($sql);
    $row = $mysql_result->fetch_array( MYSQLI_ASSOC );

    if(isset($row)){
        echo "0";
    }else{
        echo "1";
    }
}else{
    echo "0";
}
?>


