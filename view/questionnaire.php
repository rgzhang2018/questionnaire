<!doctype html>
<html class="no-js">
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
        <li><a href="admin_index.php">首页</a></li>
        <li><a href="#">分类</a></li>
        <li class="am-active">内容</li>
    </ol>
</div>



<!--  here  -->
<div class="am-u-md-5 am-u-md-centered" style="background-color: #ffffff ;box-shadow: 5px 5px 3px"   >

    <form  action="#" method="post" class="am-form am-form-horizontal">
        <div class="am-form-group" style="text-align:center">
            <br>
            <h2>请输入问卷信息</h2>
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-3" class="col-sm-2 am-form-label">标题</label>
            <div class="col-sm-10">
                <input type="text" id="doc-ipt-3" placeholder="输入问卷标题">
            </div>
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="col-sm-2 am-form-label">描述</label>
            <div class="col-sm-10">
                <textarea id="doc-ta-1" placeholder="描述一下你的问卷吧" rows="4"></textarea>
            </div>
        </div>

        <div class="am-form-group">
            <div class="am-u-sm-4">
                <button type="button" class="am-u-sm-9 am-btn am-btn-primary  am-round">添加单选</button>
            </div>
            <div class="am-u-sm-4">
                <button type="button" class="am-u-sm-9 am-u-sm-centered am-btn am-btn-secondary  am-round">添加多选</button>
            </div>
            <div class="am-u-sm-4">
                <button type="button" class="am-u-sm-9 am-btn am-btn-success  am-round">添加问答</button>
            </div>



        </div>

        <div class="am-form-group"><br></div>

            <div class="am-form-group">
                <div class="am-u-sm-6">
                    <button type="submit" class="am-btn am-btn-default am-btn-block" >完成提交</button>
                </div>
                <div class="am-u-sm-6">
                    <button class="am-btn am-btn-default am-btn-block" >重置问卷</button>
                </div>
            </div>
            <div class="am-form-group">
                <br>
            </div>
    </form>
</div>





<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="../assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="../assets/js/amazeui.min.js"></script>
</body>
</html>