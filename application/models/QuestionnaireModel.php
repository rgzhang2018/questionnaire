<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/29
 * Time: 10:18
 */


class QuestionnaireModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->x = $this->load->database();
    }

    public function insertQuestionnaire($u_id,$title,$describe,$questions,$answers){
    /**
     * 问卷的添加控制过程：
     * DataBaseController 处理信息-》 QuestionnaireModel的insertQuestionnaire（此处）
     * insertQuestionnaire -》 Questionnaire类（命名空间：writeQuestionnaire）
     * Questionnaire类 -》内部调用qQuestion类（命名空间：writeQuestionnaire） 以事务方式插入整个问卷
     */
        include_once "qQuestion.php";
        include_once "Questionnaire.php";
        $thisq = new writeQuestionnaire\Questionnaire($u_id,$title,$describe,$this->db);
        for($i=0;$i<sizeof($questions);$i++){
            $thisq->addQuestion($questions[$i][1],$answers[$i],$questions[$i][0]);
        }
        $flag = $thisq->submit();
        return $flag;
    }


    public function test(){
        include_once "qQuestion.php";
        include_once "Questionnaire.php";

//        $name="测试问卷11111";
//        $thisq = new writeQuestionnaire\Questionnaire(1,$name,"这是第一份测试问卷，内容包括2个单选，1个多选，1个大题",$this->db);
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

//
//        include "text.php";
//        $t = new \readQuestionnaire\text($this->db);
//        $t->test();
    }
}