<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 2:47
 */

class UserView extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    //下面是控制显示部分的信息
    public function adminIndex(){
        $arr['title'] = "个人中心";
        $this->showPage('user_admin.php',$arr);
    }

    public function addQuestionnaire(){
        $arr['title'] = "添加问卷";
        $this->showPage('user_addQuestionnaire.php',$arr);
    }

    public function overViewQuestionnaire(){
        $arr['title'] = "问卷总览";
        isset($_SESSION) OR session_start();
        $u_id = $_SESSION['u_id'];
        $this->load->model('QuestionnaireModel');
        $arr['questions'] = $this->QuestionnaireModel->getAllQuestionnaireByUID($u_id);
        $this->showPage('user_overViewQuestionnaire.php',$arr);
    }

    //问卷总览页面的控制器，可以进行问卷的预览、结果预览、以及删除指定问卷
    public function overViewControl(){
        if(!isset($_POST['q_id']))$this->$this->errorMessage("错误的指向");
        isset($_SESSION) OR session_start();
        $_SESSION['q_id'] = $_POST['q_id'];
        if(isset($_POST['look'])){
            header('Location: ../UserView/preview');
        }elseif (isset($_POST['check'])){
            header('Location: ../UserView/questionnaireResult');
        }elseif (isset($_POST['delete'])){
            header('Location: ../DatabaseController/deleteByID');
        }
    }

    //问卷预览界面
    public function preview(){
        $arr['title'] = "预览问卷";
        session_start();
        $q_id = $_SESSION['q_id'];
        $this->load->model('QuestionnaireModel');
        $arr['questions'] = $this->QuestionnaireModel->getQuestionnaireByID($q_id);
        $this->showPage('user_preview.php',$arr);
    }

    //显示某个问卷的结果信息
    public function questionnaireResult(){
        $arr['title'] = "问卷结果统计";
        session_start();
        $q_id = $_SESSION['q_id'];
        $this->load->model('QuestionnaireModel');
        $arr['questions'] = $this->QuestionnaireModel->getQuestionnaireByID($q_id);
        $this->showPage('user_questionnaireResult.php',$arr);
    }

    public function changePassword(){
        $arr['title'] = "修改密码";
        $this->showPage('user_changePassword.php',$arr);
    }


    // 注销
    public function logout(){
        header('Content-type:text/html; charset=utf-8');
        session_start();
//        $username = $_SESSION['username'];  //用于后面的提示信息
        $_SESSION = array();
        session_destroy();
        setcookie('username', '', time()-999);
        setcookie('code', '', time()-999);
        // 提示信息
        $this->successMessage("注销成功！即将转跳主页","../VisitorView/index");
    }


    //下面总的渲染函数
    private function showPage($pageName,$arr){
        $arr['username'] = $this->getUsername();
        $this->load->view('userHead.php',$arr);
        $this->load->view('userNav.php');
        $this->load->view('userTopbar.php');
        $this->load->view($pageName);
        $this->load->view('userFooter.php');
    }


    private function getUsername(){
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
        if(isset($_SESSION['username'])){
            $userName = $_SESSION['username'];
            return $userName;
        }else {
            $this->errorMessage("请您先登录！");
            return null;
        }
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