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
        $this->load->database();
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


    public function getQuestionnaireByID($q_id){
        include_once "reader.php";
        $reader = new \readQuestionnaire\reader($this->db);
        $result = $reader->getQuestionnaireByID($q_id);
        return $result;
    }

    public function getAllQuestionnaireByUID($u_id){
        include_once "reader.php";
        $reader = new \readQuestionnaire\reader($this->db);
        $result = $reader->queryQuestions($u_id);
        return $result;

    }
}