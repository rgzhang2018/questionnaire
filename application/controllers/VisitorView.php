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
        $arr['title']  = "成功!";
        $this->showPage('mySuccess.php',$arr);
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