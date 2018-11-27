<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 13:18
 */

?>

<style type="text/css">

    #bg_index{
        background: url(../../assets/myImg/newResigner.jpg);
        box-sizing: border-box;
        max-width: 100%;
        height: 700px;
        vertical-align: middle;
        border: 0;
    }

</style>



<body style="background-color: #e9e9e9">

<div class="am-u-md-12" id="bg_index" >
    <br>
    <br>
    <br>

    <!--  here  -->

    <div class="am-u-md-5 am-u-sm-centered"  style="background-color: #FFFFFF ;box-shadow: 10px 10px 5px">
        <hr>
        <br>
    <form class="am-form am-form-horizontal" action="questionnaire.php" method="get">
        <div class="am-form-group">
            <br>
        </div>

        <div class="am-form-group" style="text-align:center">
            <h1>问卷选择</h1>
            <hr>
        </div>


        <div class="am-form-group">
            <label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label">识别码</label>
            <div class="am-u-sm-10">
                <input type="text" name="q_id"  placeholder="请输入识别码">
            </div>
        </div>

        <div class="am-form-group">
            <div class="am-u-sm-6 ">
                <a href="../VisitorView/writeQuestionnaire/"><button type="submit" name="check"  class="am-btn am-btn-primary am-fr">提交检查</button></a>
            </div>
        </div>
        <div class="am-form-group">
            <hr>
            <br>
        </div>
    </form>
</div>
</div>
