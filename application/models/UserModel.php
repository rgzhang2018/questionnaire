<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/24
 * Time: 19:34
 */

class userModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function newUser($arr){
        $flag = $this->db->insert('user', $arr);
        return $flag;
    }

    public function checkEmail($email){
        $sql="SELECT * FROM user WHERE u_email LIKE '{$email}';";
        $query = $this->db->query($sql);
        $row =$query->result_array();
        if(sizeof($row)==1){
            return "0";
        }else{
            return "1";
        }
    }

    public function userLogin($email,$password,$remember=null){
        $sql="SELECT * FROM user WHERE u_email LIKE '{$email}';";
        $query = $this->db->query($sql);
        $arr =$query->result_array();
        $message = $arr[0];
        if(sizeof($message)==0 || $message['u_password']!=$password){
            return false;
        }
        //下面是获取数据库信息之后的操作
        # 用户名和密码都正确,将用户信息存到Session中
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $message['u_name'];
        $_SESSION['u_id'] = $message['u_id'];
        $_SESSION['islogin'] = 1;
        // 若勾选7天内自动登录,则将其保存到Cookie并设置保留7天
        if ($remember != null){
            setcookie('email', $email, time()+7*24*60*60);
            setcookie('username', $message['u_name'], time()+7*24*60*60);
            setcookie('u_id', $message['u_id'], time()+7*24*60*60);
            setcookie('code', md5($email.md5($password)), time()+7*24*60*60);
        } else {
            // 没有勾选则删除Cookie
            setcookie('email', '', time()-999);
            setcookie('username', '', time()-999);
            setcookie('code', '', time()-999);
            setcookie('u_id', '', time()-999);
        }
        return true;
    }




}