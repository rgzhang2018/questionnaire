<?php
header('Content-type:text/html; charset=utf-8');

include "./class/questionnaire.php";

$thisq = new questionnaire(1,"没啥用","我是来取出问题的，这个没啥用");

$get = $thisq->getQuestionnaireByID(2);

var_dump($get);

