<?php
///**
// * Created by PhpStorm.
// * User: rgzhang2018
// * Date: 2018/11/3
// * Time: 9:39
// * 注：此部分目前被搬运到了questionnaire.php中
// */

//class qQuestion
//{
//    protected $q_mysqli;
//    protected $q_id;         //问卷的编号，用于插入题目
//    protected $qq_id;        //题目的编号，用于插入选项
//    protected $question;
//    protected $options=[];
//    protected $count;
//    protected $type;         //type = 0,1,2分别表示单选、多选、问答题
//
//    public function __construct($question,$options, mysqli $mysqli,$type){
//        $this->question = $question;
//        $this->options = $options;
//        $this->q_mysqli = $mysqli;
//        $this->count = 0;
//        $this->type = $type;
//    }
//
//    public function insertQuestion($q_id){
//        $this->q_id = $q_id;
//        $message = "insert into question (q_id,qq_name,qq_type) value ('{$this->q_id}','{$this->question}','{$this->type}')";
//        $flag = $this->q_mysqli->query($message);
//        $message = "select max(qq_id) from question where q_id={$this->q_id};";
//        $id = $this->q_mysqli->query($message);
//        $row = $id->fetch_array( MYSQLI_ASSOC );
//        $this->qq_id= $row["max(qq_id)"];        //把题目编号放入，便于插入选项
//        echo "$this->qq_id";
//        return $flag;
//    }
//
//    public function insertOptions(){
//        if($this->type==2)return true;  //问答题无选项
//        $flag = true;
//        foreach ($this->options as $option){
//            $message = "insert into selection (qq_id,qs_order,qs_name,qs_counts)
//value ('{$this->qq_id}','{$this->count}','{$option[$this->count]}','0')";
//            $flag = $this->q_mysqli->query($message);
//            if(!$flag)return $flag;
//            $this->count++;
//        }
//        echo "2222";
//        return $flag;
//    }
//
//
//
//}
