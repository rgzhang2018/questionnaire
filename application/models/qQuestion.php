<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/30
 * Time: 15:54
 */


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

//问题类，存在于Questionnaire类内部
class qQuestion
{
    protected $q_mysqli;
    protected $q_id;         //问卷的编号，用于插入题目
    protected $qq_id;        //题目的编号，用于插入选项
    protected $question;
    protected $options=[];
    protected $count;
    protected $type;         //type = 0,1,2分别表示单选、多选、问答题

    public function __construct($question,$options,$mysql,$type){
        $this->question = $question;
        $this->options = $options;
        $this->q_mysqli = $mysql;
        $this->count = 0;
        $this->type = $type;

    }

    public function insertQuestion($q_id){
        $this->q_id = $q_id;
        $message = "insert into question (q_id,qq_name,qq_type) values ('{$this->q_id}','{$this->question}','{$this->type}');";
        $flag = $this->q_mysqli->query($message);       //返回当前题目的编号
        $this->qq_id = $this->q_mysqli->insert_id();
        return $flag;
    }

    public function insertOptions(){
        if($this->type===2)return true;  //问答题无选项
        $flag = true;
        foreach ($this->options as $option){
            $message = "insert into selection (qq_id,qs_order,qs_name,qs_counts) 
    values ('{$this->qq_id}','{$this->count}','{$option}','0');";
            $flag = $this->q_mysqli->query($message);
            if(!$flag)return $flag;
            $this->count++;
        }
        return $flag;
    }
}


