<?php

namespace writeQuestionnaire;

/**
 * 问卷类和问题类，二者之间为聚合关系，外部调用问卷类即可。
 * 问卷类包括如下方法：
 * __construct($userID, $q_name, $describe)：问卷属于单个用户，构造须包括用户名，问卷名，问卷描述
 *                                           创建时会默认加入开始、截止时间功能，开始时间为当前时间，截止时间为后推一个月
 * setQName($name, $describe); 可以利用这个函数重置问卷名称和问卷描述
 * addQuestion($name, $choice,$type); 加入问题和选项。问题是string，选项为string类型的数组(下标没有要求)，目前没有长度限制，超长会在数据库事物阶段报错
 *                                    type目前可以取0，1，2，分别表示单选、多选、问答。如果是问答题，直接将$choice传入null即可
 * setTime($startTime = null, $endTime = null); 可以单独设置开始/截止时间，未设置则默认当前开始，一个月之后截止
 * submit(); 调用submit()函数会自动提交当前整个问卷，包括已经插入的题目。提交过程以事务进行，失败不会导致数据库残留项。失败原因会在对应阶段抛出异常。
 *           如果判断的flag没有返回值，多半是插入的格式问题，如文字编码等
 **/


class Questionnaire
{

    private $userID;    //userID是插入的时候的问卷的外键，必须要有
    private $q_id;      //这个问卷的id，是插入问题的时候的外键，通过返回值获得
    private $q_name;
    private $describe;
    private $questions = [];     //问题类的数组
    private $count ;            //记录问题总数
    private $startTime ;
    private $endTime ;
    private $mysqli;

    public function __construct($userID, $q_name, $describe,$CI_mysql){
        $this->count = 0;
        $this->userID = $userID;
        $this->q_name = $q_name;
        $this->describe = $describe;
        $this->setTime();

        $this->mysqli = $CI_mysql;

    }

    public function setQName($name, $describe){
        $this->q_name = $name;
        $this->describe = $describe;
    }

    public function addQuestion($name, $choice,$type){
        $obj = new qQuestion($name,$choice,$this->mysqli,$type);
        $this->questions[$this->count] = $obj;
        $this->count++;
    }

    public function setTime($startTime = null, $endTime = null){
        if($startTime!=null){
            $this->startTime = $startTime;
        }else{
            $this->startTime = time();  //如果没有设置时间，就随便来一个
        }
        if($endTime!=null){
            $this->endTime = $endTime;
        }else{
            $this->endTime = time()+2592000;    //默认一个月
        }
    }

    public function submit(){
        $message = "begin;";
        $flag = $this->mysqli->query("$message");
        if(!$flag){
            echo "wrong submit in begin!";
            return;
        }

        try{
            //step1:插入问卷名词和标题到questionnaire
            $message = "insert into questionnaire (u_id,q_name,q_describe,q_starttime,q_endtime) 
            values ('$this->userID','$this->q_name','$this->describe','$this->startTime','$this->endTime');";
            $flag = $this->mysqli->query("$message");
            $this->q_id = $this->mysqli->insert_id();

            //问题2：插入的选项这些是否应该直接包含他们自身的编号(1-9)来作为主码的一部分，而不是单独再设置主码？
            //目前的解决：不管在数据库的编号是否是1-9，只要保证插入顺序和自己的id，就可以有序的取出来
            $this->insertQuestion();

        }catch (exception $exception){
            //只要上方出了问题，就执行会滚操作，防止出现错误的选择题
            $message = "rollback;";
            $this->mysqli->query("$message");
//            echo "Wrong submit! rollback";
            echo $exception;
            return -1;
        }
        $message = "commit;";
        $flag = $this->mysqli->query("{$message}");
//        echo "<br>OK!flag = {$flag}";
        return $this->q_id;
    }

    //下面是对单选、多选、问答题的插入,用关键词insert
    private function insertQuestion(){
        for($i=0;$i<$this->count;$i++){
            $flag1 = $this->questions[$i]->insertQuestion($this->q_id);
            $flag2 = $this->questions[$i]->insertOptions();
//            echo "<br>返回了问卷类，flag1= {$flag1}  flag2 = {$flag2} <br>";
            if(!($flag1&&$flag2))throw new Exception("wrong in insertQuestion,{$flag1},{$flag2}");
        }
    }

}
