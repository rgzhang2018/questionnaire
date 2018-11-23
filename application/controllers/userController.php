<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 2:47
 */

class UserController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    //下面是控制显示部分的信息
    public function adminIndex(){
        $message = $this-> welcomeMessage();
        $this->load->view('userHead');
        $this->load->view('userAdmin/');
        $this->load->view('userFooter/footer');
    }

    public function addQuestionnaire(){

    }

    public function overViewQuestionnaire(){

    }

    public function preview(){

    }




    //下面控制model部分
    public function logincontrol(){

    }

    private function welcomeMessage(){
        // 开启Session，存储cookie
        session_start();
        // 首先判断Cookie是否有记住了用户信息
        if (isset($_COOKIE['username']) && !isset($_SESSION['username'])) {
            # 若记住了用户信息,则直接传给Session
            $_SESSION['username'] = $_COOKIE['username'];
            $_SESSION['email'] = $_COOKIE['email'];
            $_SESSION['u_id'] = $_COOKIE['u_id'];
            $_SESSION['islogin'] = 1;
        }
        $message = "";
        if(!isset($_SESSION['islogin']))$message = "你好! ".$_SESSION['username']. ' ,欢迎来到个人中心! <a href="../controller/logout.php" >|点击注销|</a><br>';
        else $message = "您还没有登录！三秒后转跳到<a href='/visitorview/login'>登录</a>界面";
        return $message;
    }
}