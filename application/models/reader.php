<?php


namespace readQuestionnaire;


/**
 * Class reader
 * 用于读取某问卷的ID或者某用户所有问卷
 *
 * 读取某个问卷的ID时，可以不设置用户，作为游客填写时的根据，此时不填u_id即可，将u_id设置为1，本系统里默认是游客身份
 *
 * 读取某用户全部问卷必须严格匹配
 *
 * getQuestionnaireByID($q_id);  游客权限即可，输入问卷ID，得到完整的问卷信息，信息格式为：
 *
 * array(k) {
 *          ["questionnaire"]=>array(6) {
 *                   ["q_id"]=>string(1) "2"             //问卷ID
 *                  ["u_id"]=>string(1) "1"             //问卷对应用户id
 *                  ["q_name"]=>string(13) "测试问卷1"      //问卷题目
 *                  ["q_describe"]=>string(78) "这是第一份测试问卷，内容包括2个单选，1个多选，1个大题"   //问卷描述
 *                  ["q_starttime"]=>string(10) "1541256480"        //起止时间
 *                  ["q_endtime"]=>string(10) "1543848480"
 *           }
 *          [0]=>array(k) {         //下标信息：0-k-1是选项，question下标是问题
 *                  [0]=>array(5) {
 *                      ["qs_id"]=>string(1) "1"                  //对应问题的ID（唯一）
 *                      ["qq_id"]=>string(1) "1"                  //这个选项的ID，并非次序（唯一）
 *                      ["qs_order"]=>string(1) "0"               //这是该选项的顺序，可以不用管，读出的数组就是正确顺序的
 *                      ["qs_name"]=>string(9) "这是选项"          //这是选项
 *                      ["qs_counts"]=>string(1) "0"              //计数，目前这个还没用到
 *                  }
 *                  [1]=>array(5) {
 *                      .....                                     //内容同上，共k个选项，则有k-1这样的的下标
 *                  }
 *                  ["question"]=>array(4) {
 *                      ["qq_id"]=>string(1) "1"                    //这个题目自己的ID
 *                      ["q_id"]=>string(1) "2"                     //题目对应的问卷ID
 *                      ["qq_name"]=>string(60) "问题1(单选题)：什么是正确的sql表删除顺序？"      //这是题目
 *                      ["qq_type"]=>string(1) "0"
 *                  }
 *          }
 *          [1]=>array(k) {
 *                  同上，整个问题+选项的信息
 *          }
 *          ...
 * }
 *
 * queryQuestions();   查询类里的u_id对应的所有问卷（格式同上的["questionnaire"]下标）
 *
 * checkQ_id($q_id);   检查是否有这么个问卷
 *
 */

class reader
{
    private $mysqli;


    public function __construct($mysqli)
    {

        //下面设置数据库的内容
        $this->mysqli = $mysqli;

    }




    //返回该用户的某个q_id的问卷的所有信息
    public function getQuestionnaireByID($q_id){
        //查询问卷信息，返回只有一项
        //注释掉的部分为原mysqli的操作,现在改用CI框架重构
        $queryMessage = "SELECT * FROM questionnaire where q_id = {$q_id};";
        $mysql_result = $this->mysqli->query($queryMessage);
        $row =$mysql_result->result_array();
//        $row = $mysql_result->fetch_array( MYSQLI_ASSOC );
        $questionnaire = $row[0];


        //查询选项信息，返回多个选项
        $queryMessage = "SELECT * FROM question where q_id = {$q_id};";
        $mysql_result = $this->mysqli->query($queryMessage);
        $questions = [] ;
        $count = 0;
//        while( $temp1 = $mysql_result->fetch_array( MYSQLI_ASSOC )){
//            $questions [$count++] = $temp1;
//        }
        foreach ($mysql_result->result_array() as $row)
        {
            $questions [$count++] = $row;
        }


        $all_selections = [];         //这个q_selections是存放的所有的选项，每个选项以题目为下标区分开
        $countQuestions = 0;
        //查询得到选项信息
        foreach ($questions as $question){
            $qq_id = $question['qq_id'];
            $queryMessage = "SELECT * FROM selection where qq_id = {$qq_id};";
            $mysql_result = $this->mysqli->query($queryMessage);

            $selections = [] ;                         //存放当前的qq_id对应的题目的所有选项
            $countSelection = 0;                     //用于记录当前问题有多少个选项，选项作为下标
//            while( $temp2 = $mysql_result->fetch_array( MYSQLI_ASSOC )){
//                $selections [$countSelection++] = $temp2;
//            }
            foreach ($mysql_result->result_array() as $row){
                $selections [$countSelection++] = $row;
            }
            $all_selections[$countQuestions++] = $selections;   //把当前题目的选项按类分开放到所有的选项里面
        }

        //整合所有信息，方便返回
        $all_questions = [];
        $all_questions['questionnaire'] =  $questionnaire;
        $i=0;
        foreach ($questions as $question){
            $temp = $all_selections[$i];
            $temp['question'] = $question;
            $all_questions[$i] = $temp;
            $i++;
        }
//        var_dump($all_questions);
        return $all_questions;
    }

    //返回某个用户的所有问卷（只返回问卷题目和id等信息）
    public function queryQuestions($u_id){
        $queryMessage = "SELECT * FROM questionnaire where u_id={$u_id};";
        $mysql_result = $this->mysqli->query($queryMessage);
        $arrs = [] ;
        $count = 0;
        foreach ($mysql_result->result_array() as $row){
            $arrs [$count++] = $row;
        }
        return $arrs;
    }

    //检测是否存在某个问卷
    public function checkQ_id($q_id){
        $queryMessage = "SELECT * FROM questionnaire where q_id = {$q_id};";
        $mysql_result = $this->mysqli->query($queryMessage);
        $row = $mysql_result->result_array();
        return isset($row[0]["q_name"]);
    }
}