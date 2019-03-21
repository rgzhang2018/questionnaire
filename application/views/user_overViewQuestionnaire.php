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


//include_once "../model/reader.php";



?>


<div class="am-u-md-12" style="background-color: #ffffff ; height: 700px;">
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-10 am-u-md-centered" >


        <div class="am-form-group" style="text-align:center">
            <br>
            <h2>我的问卷</h2>
        </div>
        <div class="am-form-group">
            <table class="am-table am-table-bordered am-table-radius am-table-striped">
                <thead>
                <tr>
                    <th>识别码</th>
                    <th>问卷名</th>
                    <th>描述</th>
                    <th>发布时间</th>
                    <th>预览问卷</th>
                    <th>统计情况</th>
                    <th>删除问卷</th>
                </tr>
                </thead>
                <tbody>

                <?php
//                $count = 0;
                foreach ($questions as $question){
                    echo "<form action=\"../UserView/overViewControl\" method=\"post\" class=\"am-form am-form-horizontal\">";
                    echo "<tr>";
                    echo "<td>{$question['q_id']}</td>";
                    echo "<td>{$question['q_name']}</td>";
                    echo "<td>{$question['q_describe']}</td>";
                    $time = date("Y-m-d H:m:s",$question['q_starttime']);
                    echo "<td>{$time}</td>";
                    echo "<td><button type=\"submit\" name=\"look\" class=\"am-btn am-btn-default am-btn-block\">查看预览</button></td>";
                    echo "<td><button type=\"submit\" name=\"check\" class=\"am-btn am-btn-default am-btn-block\">查看统计</button></td>";
                    echo "<td><button type=\"submit\" name=\"delete\" class=\"am-btn am-btn-default am-btn-block\">点击删除</button></td>";
                    echo "</tr>";
                    echo "<input type=\"hidden\" name=\"q_id\" value={$question['q_id']}>"; //设置隐藏提交的q_id
                    echo "</form>";
                }
                ?>




                </tbody>
            </table>
        </div>


        <div class="am-form-group">

        </div>

    </div>
</div>

