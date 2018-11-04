<?php


class reader
{
    private $mysqli;
    private $u_id;

    public function __construct($u_id)
    {
        $this->u_id = $u_id;
        //下面设置数据库的内容
        $host001 = '127.0.0.1';
        $username001 = 'admin001';
        $password001 = 'a530371306';
        $dataBase001 = 'schema1';
        $this->mysqli = new mysqli($host001,$username001,$password001,$dataBase001);
        //错误判断
        if($this->mysqli->connect_errno <> 0 ){
            echo "connect fail!";
            echo $this->mysqli->connect_error;
        }
        //设置传输过去的编码格式为utf-8（注意，没有'-'）
        $flag = $this->mysqli->query("SET NAMES UTF8");
    }




    //返回该用户的某个q_id的问卷的所有信息
    public function getQuestionnaireByID($q_id){
        //查询问卷信息，返回只有一项
        $queryMessage = "SELECT * FROM questionnaire where q_id = {$q_id};";
        $mysql_result = $this->mysqli->query($queryMessage);
        $row = $mysql_result->fetch_array( MYSQLI_ASSOC );
        $questionnaire = $row;

        //查询选项信息，返回多个选项
        $queryMessage = "SELECT * FROM question where q_id = {$q_id};;";
        $mysql_result = $this->mysqli->query($queryMessage);
        $questions = [] ;
        $count = 0;
        while( $temp1 = $mysql_result->fetch_array( MYSQLI_ASSOC )){
            $questions [$count++] = $temp1;
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
            while( $temp2 = $mysql_result->fetch_array( MYSQLI_ASSOC )){
                $selections [$countSelection++] = $temp2;
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

        return $all_questions;
    }

    //返回该用户的所有问卷（只返回问卷题目和id等信息）
    public function queryQuestions(){
        $queryMessage = "SELECT * FROM questionnaire where u_id={$this->u_id};";
        $mysql_result = $this->mysqli->query($queryMessage);
        $arrs = [] ;
        $count = 0;
        while( $row = $mysql_result->fetch_array( MYSQLI_ASSOC )){
            $arrs [$count++] = $row;
        }
        return $arrs;
    }
}