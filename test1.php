<?php

header('Content-type:text/html; charset=utf-8');

include "./myModel/questionnaire.php";


$name="测试问卷1";
$thisq = new Questionnaire(1,$name,"这是第一份测试问卷，内容包括2个单选，1个多选，1个大题");

$question1 = "问题1(单选题)：什么是正确的sql表删除顺序？";
$type1 = 0;
$answer1 = ["随便删","从主表往分支删除","从分支往主表删除"];
//这里插入一个单选
$thisq->addQuestion($question1,$answer1,$type1);

$question2 = "问题2(单选题)：如何在插入后得到当前的主码值？";
$type2 = 0;
$answer2 = ["不知道","重新查询一次","利用mysqli->insert_id"];
//这里插入一个单选
$thisq->addQuestion($question2,$answer2,$type2);

$question3 = "问题3(多选题)：include头文件的时候和java的区别";
$type3 = 1;
$answer3 = ["不知道","没有","这里直接把所有内容当做文本include过来，使用相对路径会出错"];
//这里插入一个单选
$thisq->addQuestion($question3,$answer3,$type3);

$question4 = "问答题：简述如何插入整个的问卷";
$type4 = 2 ;
$thisq->addQuestion($question4,null,$type4);

$thisq->submit();

echo "插入完成！";