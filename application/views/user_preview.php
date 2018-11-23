<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 16:07
 */

?>


<!--  here  -->
<div class="am-u-md-12" style="background-color: #ffffff ">
    <div class="am-u-md-7 am-u-md-centered" >

        <form  action="#" method="post" class="am-form am-form-horizontal">

            <div>问卷预览:</div>
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
                                    echo "<label><input type=\"radio\" name=\"{$item["qq_id"]}\" value=\"{$item["qs_order"]}\">{$item["qs_order"]}.{$item["qs_name"]}</label><br>";
                                }
                                echo "</div>";
                            }elseif($type==1){
                                echo "<div class=\"am-checkbox\">";
                                foreach ($question as $item){
                                    if(isset($item["q_id"]))continue;
                                    $item["qs_order"]++;
                                    echo "<label><input type=\"checkbox\">{$item["qs_order"]}.{$item["qs_name"]}</label><br>";
                                }
                                echo "</div>";
                            } else{
                                echo " <textarea  placeholder=\"随便说点啥吧\" rows=\"5\" name=\"text1\" ></textarea>";
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