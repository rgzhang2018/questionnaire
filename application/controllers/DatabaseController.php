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


    public function addQuestion(){
        //添加整个问卷，接收POST方法
        $message = $_POST['message'];
        $questionnaire = json_decode($message);

        if(strlen($questionnaire[0][0])<=2 || sizeof($questionnaire)<=1){   //标题信息太短，或者没有题目，则返回信息有误
            echo "0";
            return ;
        }
        //下面对接收到的问卷信息进行转存
        $title = $questionnaire[0][0];
        $describe = $questionnaire[0][1];
        session_start();
        $u_id = $_SESSION['u_id'];
        $questions = [];            //$questions用于接收转存后的题目信息(除去空数组)
        $answers = [];
        for($i = 1; $i<sizeof($questionnaire);$i++){
            $tempQuestion = [];
            $tempAnswer = [];
            $tempQuestion[0] = $questionnaire[$i][0];       //0位置存放的type
            $tempQuestion[1] = $questionnaire[$i][1];       //1位置存放的问题题目
            for($j = 2;$j<sizeof($questionnaire[$i]);$j++){
                //转存的过程：过滤掉空的选项
                if(strlen($questionnaire[$i][$j])>=1)$tempAnswer[$j-2] =$questionnaire[$i][$j];   //0下标是type，取0,1,2分别表示单选、多选、问答，1下标是问题，2下标往后是选项
            }
            $questions[$i-1] = $tempQuestion;   //i-1:新的下标从0开始记录
            $answers[$i-1] = $tempAnswer;
        }
        //检查过滤后的问题信息是否完整，不完整则返回错误(由于可能存在空数组，因此需要转存后进行信息流格式确认)
        for($i = 0; $i<sizeof($questions);$i++){
            if(strlen($questions[$i][1])<=2 || sizeof($answers[$i])<1 ){  //题目内容太短，或者只有一个选项，返回信息有误
                echo "0";
                return;
            }
        }
        //检查完成，将数据传给数据库，成功则返回问卷识别码
        $this->load->model('QuestionnaireModel');
        $message = $this->QuestionnaireModel->insertQuestionnaire($u_id,$title,$describe,$questions,$answers);

        if($message==-1)echo "插入失败";
        else echo "插入成功！问卷的唯一识别码是{$message}";
        return;

    }

    public function test(){

    }

    public function getMessageBoard(){
        $this->load->model('MessageBoardModel');
        $message = $this->MessageBoardModel->getAllMessage();
        return $message;
    }

    public function addMessage(){
        if(!isset($_POST['commit'])){
            $this->errorMessage("错误的提交！");
        }
        session_start();
        $time = time();
        $text = $_POST['text1'];
        $name = $_POST['text2'];
        $captcha = $_POST["captcha"];
        $u_id = 1;          //默认为1，表示游客
        if(isset($_SESSION["u_id"])){
            $u_id=$_SESSION["u_id"];
        }
        if(strtolower($_SESSION["captcha"]) == strtolower($captcha)){
            $_SESSION["captcha"] = "";
        }else{
            $this->errorMessage("验证码错误，请重新输入！");
        }
        if(!isset($text) || !isset($name)){
            $this->errorMessage("留言信息或者留言人不能为空！");
        }
        $this->load->model('MessageBoardModel');
        $flag = $this->MessageBoardModel->saveMessage($text,$name,$time,$u_id);
        if($flag)$this->successMessage("留言成功！","../VisitorView/messageBoard");
        else $this->errorMessage("留言失败，请联系管理员！");
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