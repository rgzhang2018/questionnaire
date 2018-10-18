<?php
include('../DB/quicksql.php');
$queryMessage = "SELECT * FROM webmessage;";

$mysql_result = $db1->query($queryMessage);

if($mysql_result == false)echo "SQL语句错误!";

$arrs = [] ;
while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
 $arrs [$row['id']] = $row;
}



?>
<!doctype html>
<html class="no-js" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Hello Amaze UI</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="../assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="32x32" href="../assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="../assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="../assets/css/amazeui.min.css">
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>

<div class="am-g">
    <div class="am-u-sm-1"></div>
    <div class="am-u-sm-2 am-u-sm-offset-9"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
</div>
<div class="am-animation-scale-up am-u-sm-3 am-u-sm-centered" >
    <ol class="am-breadcrumb">
        <li><a href="../index.php">首页</a></li>
        <li><a href="#">分类</a></li>
        <li class="am-active">内容</li>
    </ol>
</div>



<!--  here  -->
<div class="am-u-md-6 am-u-md-centered">
        <h6>请输入留言：</h6>
    <form action="../control/formsave.php" method="post">
         <div class="am-u-sm-12  am-u-md-centered">
             <textarea  class="am-u-sm-12  am-u-md-centered"  name="text1" cols="30" rows="5"></textarea>
         </div>
        <div class="am-u-sm-12"><br></div>
         <div class="am-u-sm-12  am-u-md-centered">
             <p class="am-u-sm-2 " > 用户名：</p>
             <input class="am-u-sm-9 am-u-md-centered " type="text" name="text2">
         </div>
        <div class="am-u-sm-12  am-u-md-centered">
            <button type="submit" class="am-btn am-btn-block am-btn-primary">点击提交</button>
        </div>

    </form>
</div>
<div class="am-u-sm-12">
    <br>
    <hr  style="height:5px background-color:#000 ">
</div>
<div class="am-u-md-6 am-u-md-centered">

    <div class="am-u-sm-12"><h4>历史留言：</h4></div>

    <?php
    foreach ($arrs as $value) {
        ?>
        <div>
            <div>
			 	<span>
			 		<?php echo "{$value['id']}楼.用户名：{$value['name']}";	?>
			 	</span>
            </div>
            <div>
			 	<span>
			 		<?php echo "留言内容：{$value['message']}"; ?>
			 	</span>
            </div>
            <div>
			 	 <span>
			 		<?php echo "时间："; echo date("Y-m-d h:m:s",$value['time']); ?>
			 	</span>
            </div>
            <hr>
            </br>
        </div>
        <?php
    }
    ?>
</div>




<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="../assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="../assets/js/amazeui.min.js"></script>
</body>
</html>