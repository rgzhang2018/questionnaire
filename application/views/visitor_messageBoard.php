<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 13:18
 */


include('../controller/querymessage.php');
include_once "../controller/userHeader.php";

?>


<body  style="background-color: #e9e9e9">



<div class="am-u-md-6 am-u-md-centered" style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px"  >
    <form action="#" method="post" class="am-form am-form-horizontal">


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

        </div>
        <div class="am-form-group">
            <div class="am-u-md-3">
                <img src="../model/image_captcha.php" onclick="this.src='../control/getImage.php?'+new Date().getTime();" width="100" height="30">
            </div>
            <div class="am-u-md-3">
                <input type="text" name="captcha" placeholder="请输入验证码"><br/>
            </div>
            <div class="am-u-md-6">
                <button type="submit" name="commit" class="am-btn am-btn-primary" >提交</button>
            </div>
        </div>
        <div class="am-form-group"></div>
    </form >
</div>



<div class="am-u-sm-12">
    <br>
</div>
<div class="am-u-md-6 am-u-md-centered"  style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px">

    <div class="am-u-sm-12"><h4>历史留言：</h4></div>
    <br>
    <br>
    <br>
    <?php
    foreach ($arrs as $value) {
    ?>
    <div>
        <section class="am-panel am-panel-default">
            <header class="am-panel-hd">
                <span class="am-fr"><?php echo date("Y-m-d H:m:s",$value['m_time']); ?> </span>
                <h3 class="am-panel-title"><?php echo "{$value['m_id']}楼.  {$value['m_name']}:"; ?></h3>

            </header>
            <div class="am-panel-bd">
                <?php echo "{$value['m_message']}"; ?>
            </div>
        </section>
        <br>
        <?php
        }
        ?>
    </div>

</div>


