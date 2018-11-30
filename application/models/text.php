<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/30
 * Time: 16:42
 */

namespace readQuestionnaire;


class text
{
    private $mysqli;

    public function __construct($sql)
    {
        $this->mysqli = $sql;
    }


    public function test(){
        $arr = array(
          'u_id'=>"1",
          'm_message'=>'111111111',
          'm_name'=>'333333333333333333333333',
          'm_time'=>'0'
        );
        $queryMessage = "INSERT INTO messageBoard (u_id,m_message,m_name,m_time) VALUES ('1','3213123','333333322222','0');";
        $query = $this->mysqli->query($queryMessage);
//        $query = $this ->mysqli ->insert('messageboard',$arr);
//        var_dump( $query);
        $id = $this->mysqli->insert_id();
        var_dump($id);
    }
}
