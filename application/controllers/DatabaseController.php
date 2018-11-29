<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/24
 * Time: 18:53
 */

class DatabaseController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
    }

    public function newUser($u_email = null, $u_password = null, $u_name = null)
    {
        if ($u_email == null && isset($_POST['email'])) {
            $u_email = $_POST['email'];
            $u_password = $_POST['password1'];
            $u_name = $_POST['name'];
        }
        if ($u_email == null) {
            $this->errorMessage("注册失败，请检查注册信息，或者联系我改BUG...") ;
        }
        $arr = array(
            'u_email' => $u_email,
            'u_password' => $u_password,
            'u_name' => $u_name
        );
        $this->load->model('UserModel');
        $flag = $this->UserModel->newUser($arr);
        if ($flag) {
            $this->successMessage("注册成功","../VisitorView/login") ;
        } else {
            $this->errorMessage("注册失败，请检查注册信息，或者联系我改BUG...") ;
        }
    }


    //检查用户名是否存在，对应注册页面的ajax
    public function checkEmail()
    {
        $email = null;
        if (isset($_GET['email']) && $_GET['email'] != "") {
            $email = $_GET['email'];
        }
        /*判断获取的用户名是否为空*/
        if ($email == null) {
            echo "0";
        } else {
            $this->load->model('UserModel');
            $flag = $this->UserModel->checkEmail($email);
            echo $flag;
        }
    }

    public function login(){
        $email = null;
        $password = null;
        $remember = null;
        if (isset($_POST['login']) &&  $_POST['email']!='') {
            // 接收用户的登录信息
            $email = $_POST['email'];
            $password = $_POST['password'];
            if ($_POST['remember'] == "yes")$remember = 1;
        }else {
            $this->errorMessage("登录失败，信息格式有误") ;
        }
        $this->load->model('UserModel');
        $message = $this->UserModel->userLogin($email,$password,$remember);
        if($message){
            $this->successMessage("登录成功","../UserView/adminIndex");
        }else{
            $this->errorMessage("用户名或密码错误");
        }
    }

    public function test(){
        $message = $_POST['message'];
        $questionnaire = json_decode($message);
        var_dump($questionnaire);
        echo "OK";
    }

    private function errorMessage($message){
        session_start();
        $_SESSION['controlMessage'] = $message;
        header('Location: ../VisitorView/error');
        die();
    }

    private function successMessage($message,$url = null){
        session_start();
        $_SESSION['controlMessage'] = $message;
        if($url!=null)$_SESSION['nextURL'] =$url;   //设置在success位置的转跳，比如登录成功就转跳到个人主页
        header('Location: ../VisitorView/success');
        die();
    }



}