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

    public function login(){
        $arr['title']  = "用户登陆";
        $this->load->view('userHeader.php',$arr);
        $this->load->view('login.html');
    }

    public function register(){
        $arr['title']  = "用户注册";
        $this->load->view('userHeader.php',$arr);
        $this->load->view('register.html');
    }

    public function messageBoard(){
        $arr['title']  = "留言板";
        $this->load->view('userHeader.php',$arr);
        $this->load->view('messageBoard.php');
    }

    public function getQuestionnaireID(){
        $arr['title']  = "请输入问卷ID";
        $this->load->view('userHeader.php',$arr);
        $this->load->view('getQuestionID.php');
    }

    public function writeQuestionnaire(){
        $arr['title']  = "问卷填写";
        $this->load->view('userHeader.php',$arr);
        $this->load->view('writeQuestionnaire.php');
    }

    public function success(){
        $arr['title']  = "成功!";
        $this->load->view('userHeader.php',$arr);
        $this->load->view('success.php');
    }
}