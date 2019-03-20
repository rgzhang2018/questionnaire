<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/30
 * Time: 12:34
 */


//redis采用1号库存放留言板信息
class MessageBoardModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function getAllMessage(){
        $arr = null;
        $redis = new Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->select(1); //选择1号库
        $result = $redis->get("messageBoard");
        $saveFlag = $redis->get("messageBoardFlag");    //flag=true表示上次存储了新的数据
        if(strlen($result)<1 || $saveFlag == 1){
            $arr  = $this->getAllMessageFromDataBase();
            $redis->set("messageBoard",json_encode($arr));  //如果没有存放在redis里，则重新存放进redis
            $redis->set("messageBoardFlag",0);
            //测试：
            //$arr['test'] = "从mysql取得了新数据";
        }else{
            $arr = json_decode($result,true);        //true返回值是数组,否则返回值为object
            //$arr['test'] = "从redis中获取了留言板";
        }

        return $arr;
    }


    /**
    * @Description:从数据库中取出messageboard的内容
    * @Author: rgzhang
    * @Date: 2019/3/20
    */
    private function getAllMessageFromDataBase(){
        $queryMessage = "SELECT * FROM messageBoard;";
        $query = $this->db->query($queryMessage);
        $arr =$query->result_array();
        return $arr;
    }


    public function saveMessage($text,$name,$time,$u_id){
        $flag = $this->saveMessageInDataBase($text,$name,$time,$u_id);
        $redis = new Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->select(1); //选择1号库
        $redis->set("messageBoardFlag",1);      //最近存储了新的数据，告诉getAllMessage()去取
        return $flag;
    }


    /**
    * @Description:往数据库中存放messageboard的一条信息
    * @Author: rgzhang
    * @Date: 2019/3/20
    */
    private function saveMessageInDataBase($text,$name,$time,$u_id){
        $insertMessage = "INSERT INTO messageBoard (u_id,m_message,m_name,m_time) VALUES ('{$u_id}','{$text}','{$name}','{$time}');";
        $flag = $flag = $this->db->query($insertMessage);
        return $flag;
    }

}