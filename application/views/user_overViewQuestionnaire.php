<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 16:15
 */

/**
 * 问卷查看页面。这里显示全部的问卷
 */

include_once "../controller/userHeader.php";
include_once "../model/reader.php";


$questions = [];
if(isset($_SESSION['u_id'])){
    $reader = new reader($_SESSION['u_id']);
    $questions = $reader->queryQuestions();
}else{
    header("refresh:3;url=./visitor_login.html");
}


?>


<div class="am-u-md-12" style="background-color: #ffffff ">
    <div class="am-u-md-7 am-u-md-centered" >

    <form action="admin_preview.php" method="post" class="am-form am-form-horizontal">
        <div class="am-form-group" style="text-align:center">
            <h2>我的问卷</h2>
        </div>
        <div class="am-form-group">
            <table class="am-table am-table-bordered am-table-radius am-table-striped">
                <thead>
                <tr>
                    <th>问卷名</th>
                    <th>描述</th>
                    <th>发布时间</th>
                    <th>问卷状态</th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($questions as $question){
                    echo "<tr>";
                    echo "<td>{$question['q_name']}</td>";
                    echo "<td>{$question['q_describe']}</td>";
                    $time = date("Y-m-d H:m:s",$question['q_starttime']);
                    echo "<td>{$time}</td>";
                    echo "<td><button type=\"submit\" name=\"check\" class=\"am-btn am-btn-default am-btn-block\">点击查看</button></td>";
                    echo "</tr>";
                    echo "<input type=\"hidden\" name=\"q_id\" value={$question['q_id']}>"; //设置隐藏提交的q_id
                }
                ?>
<!--                <tr>-->
<!--                    <td>题目1</td>-->
<!--                    <td>这里是描述</td>-->
<!--                    <td>2018-11-3</td>-->
<!--                    <td>点击查看</td>-->
<!--                    <td>导出问卷</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td>题目1</td>-->
<!--                    <td>这里是描述</td>-->
<!--                    <td>2018-11-3</td>-->
<!--                    <td>点击查看</td>-->
<!--                    <td>导出问卷</td>-->
<!--                </tr>                <tr>-->
<!--                    <td>题目1</td>-->
<!--                    <td>这里是描述</td>-->
<!--                    <td>2018-11-3</td>-->
<!--                    <td>点击查看</td>-->
<!--                    <td>导出问卷</td>-->
<!--                </tr>                <tr>-->
<!--                    <td>题目1</td>-->
<!--                    <td>这里是描述</td>-->
<!--                    <td>2018-11-3</td>-->
<!--                    <td>点击查看</td>-->
<!--                    <td>导出问卷</td>-->
<!--                </tr>                <tr>-->
<!--                    <td>题目1</td>-->
<!--                    <td>这里是描述</td>-->
<!--                    <td>2018-11-3</td>-->
<!--                    <td>点击查看</td>-->
<!--                    <td>导出问卷</td>-->
<!--                </tr>                <tr>-->
<!--                    <td>题目1</td>-->
<!--                    <td>这里是描述</td>-->
<!--                    <td>2018-11-3</td>-->
<!--                    <td>点击查看</td>-->
<!--                    <td>导出问卷</td>-->
<!--                </tr>-->





                </tbody>
            </table>
        </div>


        <div class="am-form-group">

        </div>
    </form>
    </div>
</div>

