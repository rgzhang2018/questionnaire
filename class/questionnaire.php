<?php
/**问卷类，存放整个问卷对象*/


class qQuestion
{
    protected $q_mysqli;
    protected $q_id;         //问卷的编号，用于插入题目
    protected $qq_id;        //题目的编号，用于插入选项
    protected $question;
    protected $options=[];
    protected $count;
    protected $type;         //type = 0,1,2分别表示单选、多选、问答题

    public function __construct($question,$options,mysqli $mysqli,$type){
        $this->question = $question;
        $this->options = $options;
        $this->q_mysqli = $mysqli;
        $this->count = 0;
        $this->type = $type;

//        //下面设置数据库的内容
//        $host001 = '127.0.0.1';
//        $username001 = 'admin001';
//        $password001 = 'a530371306';
//        $dataBase001 = 'schema1';
//        $this->q_mysqli = new mysqli($host001,$username001,$password001,$dataBase001);
//        //错误判断
//        if($this->q_mysqli->connect_errno <> 0 ){
//            echo "connect fail!";
//            echo $this->q_mysqli->connect_error;
//        }
//        //设置传输过去的编码格式为utf-8（注意，没有'-'）
//        $flag = $this->q_mysqli->query("SET NAMES UTF8");
//        echo "<br>在构造题目类里，这里的sql连接建立  flag = {$flag}<br>";
    }

    public function insertQuestion($q_id){
        $this->q_id = $q_id;
        $message = "insert into question (q_id,qq_name,qq_type) values ('{$this->q_id}','{$this->question}','{$this->type}');";
        $flag = $this->q_mysqli->query($message);       //返回当前题目的编号
        $this->qq_id = $this->q_mysqli->insert_id;
        echo "<br>在插题号里，flag={$flag}，q_id={$this->q_id}，qq_id={$this->qq_id}";
        return $flag;
    }

    public function insertOptions(){
        if($this->type===2)return true;  //问答题无选项
        $flag = true;
        echo "<br>在插入选项这里<br>";
        foreach ($this->options as $option){
            $message = "insert into selection (qq_id,qs_order,qs_name,qs_counts) 
    values ('{$this->qq_id}','{$this->count}','{$option}','0');";
            $flag = $this->q_mysqli->query($message);
            if(!$flag)return $flag;
            $this->count++;
        }
        echo "<br>在插入选项这里，flag = {$flag}<br>";
        return $flag;
    }
}



class questionnaire
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


    public function __construct($userID, $q_name, $describe){
        $this->count = 0;
        $this->userID = $userID;
        $this->q_name = $q_name;
        $this->describe = $describe;
        $this->startTime = 0;
        $this->endTime = 0;

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
        echo "<br>在构造问卷类里，这里的sql连接建立  flag = {$flag}<br>";

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

//    用上方统一的插入题目方法代替
//    public function addSingle($name, $choice){   //name是选择题名，choice是选项数组
//        $obj = new qQuestion($name,$choice,$this->mysqli,0);
//        $this->questions[$this->count] = $obj;
//        $this->count++;
//    }
//
//    public function addMultiple($name,$choice){
//        $obj = new qQuestion($name,$choice,$this->mysqli,1);
//        $this->questions[$this->count] = $obj;
//        $this->count++;
//    }
//
//    public function addEssay($name){
//        $obj = new qQuestion($name,null,$this->mysqli,2);
//        $this->questions[$this->count] = $obj;
//        $this->count++;
//    }

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
            $this->q_id = $this->mysqli->insert_id;         //插入完一个问题之后，怎么及时返回当前问题的编号？——就用mysqli->insert_id
            echo "<br>在插入问卷这里，flag = {$flag}  q_id={$this->q_id}<br>";


            //问题2：插入的选项这些是否应该直接包含他们自身的编号(1-9)来作为主码的一部分，而不是单独再设置主码？
            //目前的解决：不管在数据库的编号是否是1-9，只要保证插入顺序和自己的id，就可以有序的取出来
            $this->insertQuestion();

        }catch (exception $exception){
            //只要上方出了问题，就执行会滚操作，防止出现错误的选择题
            $message = "rollback;";
            $this->mysqli->query("$message");
            echo "Wrong submit! rollback";
            echo $exception;
        }
        $message = "commit;";
        $flag = $this->mysqli->query("{$message}");
        echo "<br>OK!flag = {$flag}";
    }

    //下面是对单选、多选、问答题的插入,用关键词insert
    private function insertQuestion(){

        for($i=0;$i<$this->count;$i++){
            $flag1 = $this->questions[$i]->insertQuestion($this->q_id);
            $flag2 = $this->questions[$i]->insertOptions();
            echo "<br>返回了问卷类，flag1= {$flag1}  flag2 = {$flag2} <br>";
            if(!($flag1&&$flag2))throw new Exception("wrong in insertQuestion,{$flag1},{$flag2}");
        }



    }

}
