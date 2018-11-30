<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/30
 * Time: 12:34
 */

class MessageBoardModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllMessage(){
        $queryMessage = "SELECT * FROM messageBoard;";
        $query = $this->db->query($queryMessage);
        $arr =$query->result_array();
        return $arr;
    }

    public function saveMessage($text,$name,$time,$u_id){
        $insertMessage = "INSERT INTO messageBoard (u_id,m_message,m_name,m_time) VALUES ('{$u_id}','{$text}','{$name}','{$time}');";
        $flag = $flag = $this->db->query($insertMessage);
        return $flag;
    }

}