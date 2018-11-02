<?php
/**问卷类，存放整个问卷对象*/

class form
{
    private $name;
    private $message;
    private $single = [];
    private $count1;
    private $multiple = [];
    private $count2;
    private $essay = [];
    private $count3;        //三个计数分别表示单选、多选、问答的个数
    private $startTime ;
    private $endTime ;

    public function __construct(){
        $this->count1 = 0;
        $this->count2 = 0;
        $this->count3 = 0;

    }



    public function addSingle($name, $choice){   //name是选择题名，choice是选项数组

        $temp = [];
        $temp["name"] = $name;
        $temp["type"] = 0;          //type为0，1，2分别是单选、多选、问答
        $i=0;
        foreach ($choice as $arr){
            $temp[$i++] = $arr;
        }
        $this->single[$this->count1++] = $temp;
    }

    public function addMultiple($name,$choice){
        $temp = [];
        $temp["name"] = $name;
        $temp["type"] = 1;            //type为0，1，2分别是单选、多选、问答
        $i=0;
        foreach ($choice as $arr){
            $temp[$i++] = $arr;
        }
        $this->single[$this->count2++] = $temp;
    }

    public function addEssay($name){
        $temp = [];
        $temp["name"] = $name;
        $temp["type"] = 2;            //type为0，1，2分别是单选、多选、问答
        $this->single[$this->count3++] = $temp;
    }

    public function submit(){
        include("../DB/quicksql.php");


    }
}