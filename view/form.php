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
<body  style="background-color: #e9e9e9">

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




<div class="am-u-md-6 am-u-md-centered" style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px"  >
    <form  action="../control/formsave.php"  method="post" class="am-form am-form-horizontal">


        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="col-sm-2 am-form-label">留言</label>
            <div class="col-sm-10">
                <textarea  placeholder="随便说点啥吧" rows="5" name="text1" ></textarea>
            </div>
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-3" class="col-sm-2 am-form-label" >昵称</label>
            <div class="col-sm-10">
                <input type="text" id="doc-ipt-3" placeholder="输入你的昵称" name="text2" >
            </div>
        </div>

        <div class="am-form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="am-btn am-btn-primary" >提交</button>
            </div>
        </div>
        <div class="am-form-group"></div>
    </form action="../control/formsave.php">
</div>



<div class="am-u-sm-12">
    <br>
</div>
<div class="am-u-md-6 am-u-md-centered"  style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px">

    <div class="am-u-sm-12"><h4>历史留言：</h4></div>
    <?php
    foreach ($arrs as $value) {
        ?>
        <div>
            <div>
			 	<span>
			 		<?php echo "{$value['id']}楼.<br>用户名：{$value['name']}";	?>
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