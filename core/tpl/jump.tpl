<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>提示信息</title>
    </head>
    <body>
    <style>
        body{ margin:0; padding:0; font-family:Microsoft YaHei; font-size:18px;}
        a{ text-decoration:none; color:inherit; }
        h1 {font-size:28px;}
        h2 {font-size:18px;}
        body,div,h1,h2{margin:0 ;padding:0;border:0;}
        .zong{
            display:block; width:100%; height:100%; position:fixed; top:0; left:0; background:rgba(178,178,178,0.8); z-index:9999;
        }
        .kuang{
            width:580px; height:299px; background:#FFFFFF; top:50%; left:50%; position:absolute; z-index:10000;margin-top: -153px; margin-left: -239.5px; border-radius: 10px;
        }
        .kuang_t{
            width:94.1%;
            height:53px;
            line-height: 53px;
            color: #fff;
            background: #3A92FF;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 0 3%;
        }
        .kuang_f{
            width:94.1%;
            padding: 0 3%;
            height: auto;
            padding-top: 30px;

        }
        .kuang_f h1{
            font-size: 22px;
            color: #f00000;
            text-align: center;
        }
        .kuang_f h2{
            text-align: center;
            margin-top: 45px;
        }
        .kuang_f h2 a{
            color:#0069f8;
            text-decoration: underline;
        }
        @media screen and (min-width: 320px) and ( max-width:768px) {
            .kuang{
                width:90%;
                margin-left: 0;
                margin-top: 0;
                left:5%;
                top:30%;
                height: auto;

            }
            .kuang_f h1{
                font-size: 18px;
            }
            .kuang_f h2{
                font-size: 16px;
                margin-top: 20px;
                margin-bottom: 20px;
            }
            .kuang_f{
                padding-top: 20px;
            }
        }
    </style>



		<div class="zong">
			<div class="kuang">
				<div class="kuang_t" style="<?php echo $status?'background:#3A92FF;':'background:#F00000;';?>">操作<?php echo $status?'成功':'失败';?></div>
				<div id="msg" class="kuang_f">
					<h1><?php echo $msg??'';?></h1>
					<h2 id="jump">页面自动  <a id="href" href="<?php echo $jump_url;?>">跳转</a> 等待时间：<b id="wait"><?php echo $wait_time;?></b></h2>
				</div>
			</div>
		</div>
		<script>
			(function(){
				var wait = document.getElementById('wait'),href = document.getElementById('href').href;
				if((self != top && href == parent.location.href) || href == location.href){
					document.getElementById('jump').innerHTML = '';
					return false;
				}else{
					var interval = setInterval(function(){
						var time = --wait.innerHTML;
						if(0 == time) {
							top.location.href = href;
							clearInterval(interval);
						};
					}, 1000);
				}
			})();
		</script>
    </body>
</html>