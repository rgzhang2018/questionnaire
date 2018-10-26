<?php 
include("../DB/quicksql.php");

$queryMessage = "SELECT * FROM webmessage;";

$mysql_result = $db1->query($queryMessage);

if($mysql_result == false)echo "SQL语句错误!";

$arrs = [] ;
while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
 $arrs [$row['id']] = $row;
}


 ?>