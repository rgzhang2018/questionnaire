<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 13:17
 * 用JS控制作答信息
 * 需要传入给PHP的信息：
 * 1.问卷的id
 * 2.选择的那个选项框的qs_id和对应题目的qq_id（每个题目占一个角标）
 * 3.问答题的qq_id和对应的内容
 */


?>


<!--  here  -->
<div class="am-u-md-7 am-u-md-centered" style="background-color: #FFFFFF ;box-shadow: 5px 5px 3px"   >

    <form  action="#" method="post" class="am-form am-form-horizontal">

        <div class="am-form-group" style="text-align:center">
            <hr>
            <h2><?php  echo "{$questions["questionnaire"]["q_name"]}" ; ?></h2>
            <?php  echo "{$questions["questionnaire"]["q_describe"]}" ; ?>

        </div>
        <hr>
        <div class="am-form-group">
            <!--            这里是问卷内容-->
            <?php
            $count = 1;
            $type=0;      //表示单选、多选、问答,分别是0,1,2
            $max_qs_id = 0;  //记录最大的qs_id
            foreach ($questions as $question) {
                if(isset($question["q_id"])){   //跳过题目
                    continue;
                }
                ?>
                <section class="am-panel am-panel-default">
                    <header class="am-panel-hd">
                        <h3 class="am-panel-title">
                            <?php
                            //题目信息
                            echo "第{$count}题. {$question["question"]["qq_name"]}:";
                            $type = $question["question"]["qq_type"];
                            $qq_id = $question["question"]["qq_id"];
                            $count++;
                            ?>
                        </h3>

                    </header>
                    <div class="am-panel-bd">
                        <?php
                        //选项信息部分，根据type，分别是单选、多选、问答
                        if($type == 0){         //1.单选题
                            echo "<div class=\"am-radio\">";
                            foreach ($question as $item){
                                if(isset($item["q_id"]))continue;   //题目信息略过
                                $item["qs_order"]++;    //下面是单选选项
                                echo <<<AAAA
                                    <label><input type="radio" name="{$item["qq_id"]}" id ="{$item["qs_id"]}" >{$item["qs_order"]}.{$item["qs_name"]}</label><br>
AAAA;
                                if($item["qs_id"]>$max_qs_id)$max_qs_id=$item["qs_id"];//找出最大的qs_id
                            }
                            echo "<label id=\"q={$qq_id}\"> </label>";    //用于给出当前题目是否填写的提示信息
                            echo "</div>";

                        }elseif($type==1){
                            //2.多项选择题，按次序输出选项
                            echo "<div class=\"am-checkbox\">";

                            foreach ($question as $item){
                                if(isset($item["q_id"]))continue;
                                $item["qs_order"]++;
                                echo <<<AAAA
                                        <label><input type="checkbox"  name="{$item["qq_id"]}" id ="{$item["qs_id"]}">{$item["qs_order"]}.{$item["qs_name"]}</label><br>
AAAA;
                                if($item["qs_id"]>$max_qs_id)$max_qs_id=$item["qs_id"]; //找出最大的qs_id
                            }
                            echo "<label id=\"q={$qq_id}\"> </label>";    //用于给出当前题目是否填写的提示信息
                            echo "</div>";
                        } else{     //问答题
                            echo <<<AAAA
                                    <textarea  placeholder="随便说点啥吧" rows="5" name="{$qq_id}"></textarea>
                                    <label id="q={$qq_id}"></label>    
AAAA;
                        }
                        ?>
                    </div>
                </section>
                <br>
            <?php } ?>
        </div>
        <div class="am-form-group"><hr></div>
        <div class="am-form-group">
            <div class="am-u-sm-8 am-u-sm-centered">
                <button type="button" class="am-btn am-btn-primary am-btn-block" onclick="submitAll()">确认提交</button>
            </div>
        </div>
        <div class="am-form-group"><hr></div>
    </form>
</div>

<div id = "test0"></div>
<script>
    <?php
    //answers信息格式：
    //0下标：问卷id
    //1-n下标：
    //[0]问题id
    //[1]要么是选项id(代表问题被选中的次数)，要么是-1,代表这个题目是问答题，对应有问答题的答案信息
    echo "const q_id = {$questions["questionnaire"]["q_id"]};";
    ?>

    var answers = [];   //所有的选择情况，[0]号下标代表整个问卷的id信息，[1]号下标代表第一题的题目id、选中的id等。
    answers[0] = q_id;
    var count = 1;      //记录answers的当前下标
    /**
     * 提取过程：
     * 所有的name属性对应题目的id
     * 选择题的选项的id是自己的qs_id
     * 问答题有class = essay
     */




    function submitAll() {
        count = 1;
        let flag1 = getSelections();
        let flag2 = getEssay();
        if(!flag1 || !flag2){
            alert("信息填写不完整，请检查确认后再提交");
            return ;
        }
        let data = JSON.stringify(answers);
        ajaxPost(data);
    }


    function getEssay(){
        //获取问答题信息，放入answers里
        const essays = document.getElementsByTagName("textarea");
        for(let i = 0;i<essays.length;i++){
            let message = essays[i].value;
            let qq_id = essays[i].name;
            if(message.length <= 1){
                document.getElementById("q="+qq_id).innerHTML="<font color='red'>请填写本题</font>";
                return false;
            }else {
                document.getElementById("q="+qq_id).innerHTML="";
            }
            //将被选中的节点的信息存入对应的count数组
            answers[count] = [];
            answers[count][0] = qq_id;
            answers[count][1] = -1;
            answers[count][2] = message;
            count++;
        }
        return true;
    }


    function getSelections() {
        //获取选项信息，放入answers里
        const selections = document.getElementsByTagName("input");
        let check = [];         //check数组里记录已经被选中过的问题的qq_id
        let id = [];
        let countID = 0 ;       //记录id数组的长度，id数组里存放未被选中的问题的qq_id
        for(let i = 0;i<selections.length;i++){
            if(selections[i].checked){
                let qs_id = selections[i].id;
                let qq_id = selections[i].name;     //name是对应的题目的qq_id
                //将被选中的节点的信息存入对应的count数组
                check[qq_id] = true;
                answers[count] = [];
                answers[count][0] = qq_id;
                answers[count][1] = qs_id;
                count++;
            }else {
                let qq_id = selections[i].name;
                if(qq_id!=="")id[countID++] = qq_id;    //确认获取到正确的标签，在放入未填写id数组里
            }
        }
        let flag = true;
        for(let i = 0; i<countID;i++){
            if(check[id[i]]!==true){
                document.getElementById("q="+id[i]).innerHTML="<font color='red'>请填写本题</font>";
                flag = false;
            }else {
                document.getElementById("q="+id[i]).innerHTML=" ";   //清空未选中的提示信息
            }
        }
        return flag;
    }

    var xmlHttp;
    function ajaxPost(message){
        S_xmlhttprequest();
        xmlHttp.open("POST","../DatabaseController/writeQuestion",true);//找开请求
        xmlHttp.setRequestHeader("content-type","application/x-www-form-urlencoded");
        xmlHttp.onreadystatechange = byphp;//准备就绪执行
        xmlHttp.send("message="+message);//发送
    }
    function S_xmlhttprequest(){
        if(window.ActiveXObject){
            xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
        }else if(window.XMLHttpRequest){
            xmlHttp = new XMLHttpRequest();
        }
    }
    function byphp(){
        //判断状态
        if(xmlHttp.readyState===1){//Ajax状态
            alert("正在传送");
        }
        if(xmlHttp.readyState===4){//Ajax状态
            if(xmlHttp.status===200){//服务器端状态
                if(xmlHttp.responseText === "1"){
                    alert("提交成功！现将返回主页");
                    window.location.href="../VisitorView/index";
                }else {
                    alert("出BUG了，提交失败");
                }
                // document.getElementById("test0").innerHTML=xmlHttp.responseText;
            }
        }
    }

</script>