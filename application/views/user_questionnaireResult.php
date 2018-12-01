<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/30
 * Time: 23:26
 */

?>



<!--  here  -->
<div class="am-u-md-12" style="background-color: #ffffff ">
    <div class="am-u-md-7 am-u-md-centered" ">
    <form  action="#" method="post" class="am-form am-form-horizontal">
        <br>
        <h3>问卷作答情况:</h3>
        <div class="am-form-group" style="text-align:center">
            <hr>
            <h2><?php  echo "{$questions["questionnaire"]["q_name"]}"  ?></h2>
            <?php  echo "{$questions["questionnaire"]["q_describe"]}"  ?>

        </div>
        <hr>
        <div class="am-form-group">
            <!--            这里是问卷内容-->
            <?php
            $count = 1;
            $type=0;      //表示单选、多选、问答,分别是0,1,2
            foreach ($questions as $question) {
                if(isset($question["q_id"])){
                    continue;
                }
                ?>
                <section class="am-panel am-panel-default">
                    <header class="am-panel-hd">
                        <h3 class="am-panel-title">
                            <?php
                            echo "第{$count}题. {$question["question"]["qq_name"]}:";
                            $type = $question["question"]["qq_type"];
                            $count++;
                            ?>
                        </h3>

                    </header>
                    <div class="am-panel-bd">
                        <?php
                        if($type == 0){
                            echo "<div class=\"am-radio\">";
                            foreach ($question as $item){
                                if(isset($item["q_id"]))continue;
                                $item["qs_order"]++;
                                echo <<<AAA
{$item["qs_order"]}.{$item["qs_name"]}    <label class="am-fr">选中次数：{$item["qs_counts"]}</label><br><br>
AAA;
                            }
                            echo "</div>";
                        }elseif($type==1){
                            echo "<div class=\"am-checkbox\">";
                            foreach ($question as $item){
                                if(isset($item["q_id"]))continue;
                                $item["qs_order"]++;
                                echo <<<AAA
{$item["qs_order"]}.{$item["qs_name"]}   <label class="am-fr">选中次数：{$item["qs_counts"]}</label><br><br>
AAA;

                            }
                            echo "</div>";
                        } else{
                            echo "<p>该题目填写情况如下:</p>";
                            foreach ($question as $item){
                                if(isset($item["q_id"]))continue;
                                $item["qs_order"]++;
                                echo "<label>{$item["qs_order"]}.{$item["qs_name"]}</label><br><br>";
                            }
                        }
                        ?>
                    </div>
                </section>
                <br>
            <?php } ?>
        </div>
    </form>
</div>
</div>



