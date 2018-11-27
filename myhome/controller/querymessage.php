<?php

/**
 * 留言板信息的显示
 */

header('Content-type:text/html; charset=utf-8');
include("../model/quicksql.php");

$queryMessage = "SELECT * FROM messageBoard;";

$mysql_result = $db1->query($queryMessage);

if($mysql_result == false)echo "SQL语句错误!";

$arrs = [] ;
while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
    $arrs [$row['m_id']] = $row;
}



 ?>