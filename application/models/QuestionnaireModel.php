<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/29
 * Time: 10:18
 */

/**
 * @Description:处理问卷相关信息，使用redis的2号库，写入问卷用redis的setnx锁实现
 * @Author: rgzhang
 */
class QuestionnaireModel extends CI_Model
{
    private $selectRedis = 2;
    private $freshTime = 30;    //题目写入数据库的间隔

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


    /**
     * @Description:获取到某张问卷，作用于：user_perview，user_questionnaireResult，visitor_writeQuestionnaire
     * @param $q_id
     * @param int $freshFlag
     * @return array :
     * @Author: rgzhang
     * @Date: 2018/11/21
     */
    public function getQuestionnaireByID($q_id,$freshFlag = 0){
        if(!class_exists('Redis') || $freshFlag){
            if($freshFlag)$this->answerQuestionnaireInRedis(null,1);  //用户要查询，则将redis中剩余缓存也写入数据库，保证获取到的是最新的结果
            $result = $this->getQuestionnaireByIDInDataBase($q_id);
        }else{
            $result = $this->getQuestionnaireByIDInRedis($q_id);
        }

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

        if(class_exists('Redis')){
            $flag = $this->answerQuestionnaireInRedis($answers);
        }else{
            $flag = $this->answerQuestionnaireInDataBase($answers);
        }

        return $flag;
    }



    /**
     * @Description:写入回答，写到数据库中
     * @param $answers
     * @return bool
     */
    private function answerQuestionnaireInDataBase($answers)
    {
        //answers信息格式：
        //0下标：问卷id
        //1-n下标：
        //[i][0]问题id
        //[i][1]要么是选项id(代表问题被选中的次数)，要么是-1,代表这个题目是问答题，对应有问答题的答案信息
        //$q_id = $answers[0];
        //用事务进行提交
        $message = "begin;";
        $this->db->query($message);
        for ($i = 1; $i < sizeof($answers); $i++) {
            if ($answers[$i][1] != -1) {
                //!=-1是选择，更新被选中的次数
                $qs_id = $answers[$i][1];
                $message = "update `selection` set qs_counts=qs_counts + 1 where qs_id={$qs_id};";
                $flag = $this->db->query($message);
                if (!$flag) {
                    $message = "rollback;";
                    $this->db->query($message);
                    return false;
                }
            } elseif ($answers[$i][1] == -1) {
                $qq_id = $answers[$i][0];
                $essay = $answers[$i][2];
                $message = "insert into selection (qq_id,qs_order,qs_name,qs_counts) values ('{$qq_id}','-1','{$essay}','0');";
                $flag = $this->db->query($message);
                if (!$flag) {
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


    /**
     * @Description: 写入到redis中，选择题通过一个redis的hashset实现；问答通过一个list串接sql语句实现(同时存储问题id和解答)
     * $freshFlag=1，表示直接把redis中现有的数据入数据库，用于用户查询的时候
     * @param $answers
     * @param int $freshFlag=0
     * @return bool
     */
    private function answerQuestionnaireInRedis($answers,$freshFlag = 0){
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select($this->selectRedis);

        $thisTime = time();
        $redisTime = $redis->get('time');

        for ($i = 1; $i < sizeof($answers); $i++){
            if ($answers[$i][1] != -1) {
                $qs_id = $answers[$i][1];
                $redis->hIncrBy('selections',$qs_id,1);    //hIncrBy(hset,key,x)使得集合中key对应的值增加x

            }elseif ($answers[$i][1] == -1) {   //插入问答答案，键值较复杂，每句话都要插入新的一行
                $qq_id = $answers[$i][0];
                $essay = $answers[$i][2];
                $message = "insert into selection (qq_id,qs_order,qs_name,qs_counts) values ('{$qq_id}','-1','{$essay}','0');";
                $redis->rPush('answers',$message);
//                $this->db->query($message);
            }
        }

        if($thisTime - $redisTime >= $this->freshTime || $freshFlag ){   //超过时间间隔$freshTime，则刷新写入刚才的填写情况，采用事务写入
            if($redis->setnx('insertLock',1)){      //给redis加锁，防止多个线程重复写入(比如上面的时间条件由一个写入触发，同时flag条件由一个查询触发)
                $this->db->query("start;");
                try{

                    //取出选择情况并写入sql
                    $selections = $redis->hGetAll('selections');
                    $qs_ids = array_keys($selections);
                    //取出问答题并写入sql
                    $listLength = $redis->lLen('answers');
                    $answers = $redis->lRange('answers',0,$listLength-1);

                    $i = 0;
                    //取出选择情况并写入sql
                    foreach($selections as $counts){
                        $message = "update `selection` set qs_counts=qs_counts + {$counts} where qs_id={$qs_ids[$i]};";
                        $this->db->query($message);
                        $i++;
                    }
                    //取出问答题并写入sql
                    for ($i=0;$i< $listLength;$i++){
                        $this->db->query($answers[$i]);
                    }

                }catch (Exception $exception){
                    $this->db->query("rollback;");
                    echo $exception;
                    return false;
                }
                $this->db->query("commit;");
                $redis->del('selections');
                $redis->del('answers');
                $redis->del('insertLock');  //删除锁
                $redis->set('time',$thisTime); //记录这次写入redis的时间，作为下一个时间间隔
            }

        }
        return true;
    }

    /**
     * @param $q_id
     * @return array
     */
    private function getQuestionnaireByIDInDataBase($q_id)
    {
        include_once "reader.php";
        $reader = new \readQuestionnaire\reader($this->db);
        $result = $reader->getQuestionnaireByID($q_id);
        return $result;
    }


    /**
     * @Description:从redis中获取整张问卷
     * @param $q_id
     * @param int $freshFlag=0
     * @return array :
     * @Author: rgzhang
     * @Date: 2019/3/21
     */
    private function getQuestionnaireByIDInRedis($q_id)
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select($this->selectRedis);
        $result = $redis->get($q_id);

        if(strlen($result) < 1){
            $questionnaire = $this->getQuestionnaireByIDInDataBase($q_id);
            $redis->set($q_id,json_encode($questionnaire));
        }else{
            $questionnaire = json_decode($result,1);
        }

        return $questionnaire;
    }
}