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


    public function deleteQuestionnaireByID($q_id){
        $message = "select qq_id from question where q_id='{$q_id}';";
        $query = $this->db->query($message);
        $result=$query->result_array();
        foreach ($result as $item){
            $qq_id = $item['qq_id'];
            $message = "delete from selection where qq_id='{$qq_id}';";
            $this->db->query($message);
        }
        $message = "delete from question where q_id='{$q_id}';";
        $query = $this->db->query($message);
        $message = "delete from questionnaire where q_id='{$q_id}';";
        $flag = $this->db->query($message);
        return $flag;
    }

    public function answerQuestionnaire($answers){
        //answers信息格式：
        //0下标：问卷id
        //1-n下标：
        //[i][0]问题id
        //[i][1]要么是选项id(代表问题被选中的次数)，要么是-1,代表这个题目是问答题，对应有问答题的答案信息
//        $q_id = $answers[0];
        //用事务进行提交
        $message = "begin;";
        $this->db->query($message);
        for($i=1;$i<sizeof($answers);$i++){
            if($answers[$i][1]!=-1){
                //!=-1是选择题，插入到数据库表的count位置，统计被选中的次数
//                $qq_id = $answers[$i][0];
                $qs_id = $answers[$i][1];
                $qs_counts = 0;
                $message = "select * from selection where qs_id='{$qs_id}';"; //先取出qs_count，然后qs_count++，再插入
                $query = $this->db->query($message);
                $arr =$query->result_array();
                $qs_counts = $arr[0]['qs_counts'];
                $qs_counts++;

                $message = "update selection set qs_counts='{$qs_counts}' where qs_id={$qs_id};";
                $flag = $this->db->query($message);
                if(!$flag){
                    $message = "rollback;";
                    $this->db->query($message);
                    return false;
                }
            }elseif($answers[$i][1]==-1){
                $qq_id = $answers[$i][0];
                $essay = $answers[$i][2];
                $message = "insert into selection (qq_id,qs_order,qs_name,qs_counts) values ('{$qq_id}','-1','{$essay}','0');";
                $flag = $this->db->query($message);
                if(!$flag){
                    $message = "rollback;";
                    $this->db->query($message);
                    return false;
                }
            }
        }
        $message = "commit;";
        $this->db->query($message);
        return true;
    }
}