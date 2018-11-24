<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 4:47
 */

class VisitorView extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    //下面是CI框架的各个模块的显示方法，通过调用我的showPage($pageName,$arr);方法实现渲染
    public function index(){
        $arr['title']  = "简易问卷系统V1.2";
        $arr['pageFlag'] = 0;
        $this->showPage("welcome_message.php",$arr);
    }

    public function login(){
        $arr['title']  = "用户登陆";
        $this->showPage("visitor_login.html",$arr);
    }

    public function register(){
        $arr['title']  = "用户注册";
        $this->showPage("visitor_register.html",$arr);
    }

    public function messageBoard(){
        $arr['title']  = "留言板";
        $arr['arrs'] = $this->getMessage();
        $this->showPage("visitor_messageBoard.php",$arr);
    }

    public function getQuestionID(){
        $arr['title']  = "请输入问卷ID";
        $this->showPage("visitor_getQuestionID.php",$arr);
    }

    public function writeQuestionnaire(){
        $arr['title']  = "问卷填写";
        $this->showPage('visitor_writeQuestionnaire.php',$arr);
    }

    public function success(){
        session_start();
        $arr['title']  = "Success";
        if(isset($_SESSION['controlMessage'])){
            $arr['controlMessage'] = $_SESSION['controlMessage'];
            if(isset($_SESSION['nextURL'])) $arr['nextURL'] = $_SESSION['nextURL']; //如果设置了操作成功的转跳，则进行转跳，否则返回上一层
        }else {
            header('Location: ../visitorview/error');      //错误的调用，转调到错误信息处理
        }
        $this->showPage('mySuccess.php',$arr);
    }

    public function error(){
        session_start();
        $arr['title']  = "error";
        if(isset($_SESSION['controlMessage'])){
            $arr['controlMessage'] = $_SESSION['controlMessage'];
        }else{
            $arr['controlMessage'] = "出BUG了，也许是进行了错误的调用，请联系我改BUG";
        }
        if(isset($_SESSION['controlMessage']))$arr['controlMessage'] = $_SESSION['controlMessage'];
        $_SESSION['controlMessage'] = null;
        $this->showPage('myError.php',$arr);
    }

    //总的渲染模块
    private function showPage($pageName,$arr){
        $this->load->view('myHeader.php',$arr);
        $this->load->view($pageName);
        $this->load->view('myFooter.php');
    }

    //下面是控制model模块
    private function getMessage(){
        //制获得留言板信息
        $message = [''];
        return $message;

    }
}