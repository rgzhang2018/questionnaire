

<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>登录页面</title>

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
<body style="background-color: #e9e9e9">

<div class="am-g">
    <br>
    <div class="am-u-sm-1"></div>
    <div class="am-u-sm-3 am-u-sm-offset-8"><i class="am-icon-github am-icon-fw am-u-sm-left "></i>
        <a href="https://github.com/rgzhang2018/questionnaire">GitHub</a>
    </div>
    <br>
    <br>
</div>



<!--  here  -->

<div class="am-u-md-5 am-u-sm-centered"  style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px">
    <form class="am-form am-form-horizontal"  action="../control/login_control.php"  method="post">
        <div class="am-form-group">
            <br>
        </div>

        <div class="am-form-group" style="text-align:center">
            <h1>登录</h1>
            <hr>
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label">邮件</label>
            <div class="am-u-sm-10">
                <input type="email" name="email" id="doc-ipt-3" placeholder="输入你的电子邮件">
            </div>
        </div>

        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label">密码</label>
            <div class="am-u-sm-10">
                <input type="password" name="password" id="doc-ipt-pwd-2" placeholder="请输入密码">
            </div>
        </div>

        <div class="am-form-group">
            <div class="am-u-sm-offset-2 am-u-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name = "remember"  value="yes"> 记住我
                    </label>
                </div>
            </div>
        </div>

        <div class="am-form-group">
            <div class="am-u-sm-6 ">
                <button type="submit" name="login"  class="am-btn am-btn-primary am-fr">提交登入</button>
            </div>
            <div class="am-u-sm-6 ">
                <button type="submit" name="register" class="am-btn am-btn-success">点击注册</button>
            </div>
        </div>
        <div class="am-form-group">
            <hr>
            <br>
        </div>
    </form>
</div>




<!--[if (gte IE 9)|!(IE)]><!-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="../assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="../assets/js/amazeui.min.js"></script>
</body>
</html>