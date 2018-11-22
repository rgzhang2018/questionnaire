<?php

$thisq = new \reader\reader($_SESSION['u_id']);

$flag = $thisq->checkQ_id($q_id);

if(!$flag){
    header('refresh:3; url=../index.php');
    die("错误：未指明问卷或者问卷标识号错误，三秒后返回");
}else{
    $questions = $thisq->getQuestionnaireByID($q_id);
}


