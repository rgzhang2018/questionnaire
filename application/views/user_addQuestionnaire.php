<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 15:58
 */
/**
 * 问卷发布页面。
 * 这里的post请求内容：...
 */
include_once "../controller/userHeader.php";
?>



<!--  here  -->
<div class="am-u-md-12 am-u-md-centered" style="background-color: #ffffff ;box-shadow: 5px 5px 3px"   >
    <div class="am-u-md-7 am-u-md-centered">

        <br>
        <br>
        <form  action="#" method="post" class="am-form am-form-horizontal">
            <div class="am-form-group" style="text-align:center">
                <h1>请输入问卷信息</h1>
            </div>
            <br>
            <div class="am-form-group">
                <label for="q_name" class="col-sm-2 am-form-label">标题</label>
                <div class="col-sm-10">
                    <input type="text" id="q_name" placeholder="输入问卷标题">
                </div>
            </div>
            <br>
            <div class="am-form-group">
                <label for="q_describe" class="col-sm-2 am-form-label">描述</label>
                <div class="col-sm-10">
                    <textarea id="q_describe" placeholder="描述一下你的问卷吧" rows="4"></textarea>
                </div>
            </div>
            <br>
            <div class="am-form-group">
                <div class="am-u-sm-4">
                    <button type="button" class="am-u-sm-9 am-btn am-btn-primary  am-round" onclick="addSingle()">添加单选</button>
                </div>
                <div class="am-u-sm-4">
                    <button type="button" class="am-u-sm-9 am-u-sm-centered am-btn am-btn-secondary  am-round" onclick="addMultiple()">添加多选</button>
                </div>

                <div class="am-u-sm-4">
                    <button type="button" class="am-u-sm-9 am-u-sm-centered am-btn am-btn-secondary  am-round" onclick="addEssay()">添加问答</button>
                </div>
            </div>
            <br>

            <div class="am-form-group">
                <div class="am-u-sm-6">
                    <button type="button" class="am-btn am-btn-default am-btn-block" onclick="submitAll()">完成提交</button>
                </div>
                <div class="am-u-sm-6">
                    <button class="am-btn am-btn-default am-btn-block" >重置问卷</button>
                </div>
            </div>
            <div class="am-form-group">
                <hr>
                <h1>下面是题目预览</h1>

                <div class="am-form-group" id="addSingle">
                    <h2>单选题：</h2>

                    <br>
                </div>
                <div class="am-form-group" id="addMultiple">
                    <h2>多选题：</h2>
                    <br>
                </div>
                <div class="am-form-group" id="addEssay">
                    <h2>问答题：</h2>
                    <br>
                </div>
            </div>
        </form>
    </div>

</div>



<!--用于存放模版-->
<script type="text/javascript" src="../../assets/template-web.js"></script>


<!--下面是单选题目的添加-->
<script id="single_Question" type="text/html">
    <div class = "single">
        <div>
            <label class="singleCount">第<%=singleCount%>题</label>
            <button type="button" class ="am-fr am-btn am-btn-danger" onclick="deleteQuestion(this)">删除题目</button>
            <button type="button" class ="am-fr am-btn am-btn-default" id=<%=id%> onclick="addSingleChoice(this)">添加选项</button>
            <textarea placeholder="输入题目" class=<%=q_type%> rows="2"></textarea>
        </div>
        <div id=<%=selection%> >
            <br>
            <input type="text" placeholder="输入选项内容"  class=<%=type%>>
            <input type="text" placeholder="输入选项内容" class=<%=type%>>
        </div>
        <hr style="height:3px;border:none;border-top:3px groove skyblue;">
    </div>
</script>

<!--下面是选项的添加-->
<script id="Choice" type="text/html">
    <input type="text" class=<%=type%> placeholder="输入选项内容" >
</script>


<!--下面是多选题目的添加-->
<script id="multiple_Question" type="text/html">
    <div class = "multiple">
        <div>
            <label class="multipleCount">第<%=multipleCount%>题</label>
            <button type="button" class = "am-fr am-btn am-btn-danger" onclick="deleteQuestion(this)">删除题目</button>
            <button type="button" class = "am-fr am-btn am-btn-default" id=<%=id%> onclick="addMultipleChoice(this)">添加选项</button>
            <textarea placeholder="输入题目" class=<%=q_type%> rows="2"></textarea>
        </div>
        <div id="<%=selection%>" >
            <br>
            <input type="text" placeholder="输入选项内容"  class=<%=type%> >
            <input type="text" placeholder="输入选项内容" class=<%=type%> >
        </div>
        <hr style="height:3px;border:none;border-top:3px groove skyblue;">
    </div>
</script>

<!--下面是问答题的添加-->
<script id="essay" type="text/html" >
    <div class = "essay">
        <div>
            <label class="essayCount">第<%=essayCount%>题</label>
            <button type="button" class = "am-fr am-btn am-btn-danger" onclick="deleteQuestion(this)">删除题目</button>
        </div>
        <div>
            <textarea placeholder="输入问答题内容" class=<%=q_type%> rows="4"></textarea>
        </div>
        <hr style="height:3px;border:none;border-top:3px groove skyblue;">
    </div>
</script>


<!--用于渲染模版-->
<script>
    /**
     * 渲染过程：
     * id负责记录渲染,id用到了三处，分别是：
     *      1.script部分关联渲染，分别渲染单选、多选、问答题
     *      2.script内部负责增加选项数目的按钮，用于定位(也可用node树代替)(ID= countID)
     *      3.script内部控制添加选择题选项的div块，和上一行说的按钮呼应(ID = countID+"-singleChoice")
     *
     * class用于取出对应的题目、选项，同时负责更新题号:
     *      1.更新当前是第几题(class = "singleCount")
     *      2.class=<%=q_type%>  (如   1-1 ID为1-单选题   10-3 ID为10-问答题)
     *      3.class=<%=type%>   (如   1-selection :ID为1的题目的选项)
     */
    var singleCount = 0;    //动态添加信息，记录单/多/问答题目数量，删除则减少
    var multipleCount = 0 ;
    var essayCount = 0 ;
    var countID = 0;      //记录总数，这个数据作为id绑定固定的div，只会自增
    function addSingle() {
        singleCount++;
        countID ++;
        let id = countID ;           //这个是用于删除单选题目的那个div块，确定该块之后，将其内容置空
        let selection = countID+"-singleChoice";       //追加选项时记录内容
        let q_type = countID + "-0";
        let type = countID + "-selection";
        let data = {
            singleCount:singleCount,        //用于计数，题号
            id:id ,                         //用于添加选项
            selection:selection,            //用于添加选项
            q_type:q_type,                  //用于提交，获取题目内容
            type:type                       //用于提交，获取选项内容
        };
        var html=template('single_Question',data);      //单选题的题目，放在scrpit id=single_question下
        console.log(html);
        $("#addSingle").append(html);               //追加在id=addSingle的元素下面
    }
    function addSingleChoice(e){
        let selectionID = e.id +"-singleChoice";  //在每个题目都为所有的选项设置了一个div，在这个div里追加东西，就可以吧选项追加到对应位置
        let type = countID + "-selection";
        let data = {
            type:type
        };
        let html=template('Choice',data);
        $("#"+selectionID).append(html);
    }
    function addMultiple() {
        multipleCount++;
        countID ++;
        let id = countID ;
        let selection = countID+"-multipleChoice";
        let q_type = countID + "-1";
        let type = countID + "-selection";
        let data = {
            multipleCount:multipleCount,
            id:id ,
            selection:selection,
            q_type:q_type,
            type:type
        };
        var html=template('multiple_Question',data);
        console.log(html);
        $("#addMultiple").append(html);
    }
    function addMultipleChoice(e){
        let selectionID = e.id +"-multipleChoice";
        let type = countID + "-selection";
        let data = {
            type:type
        };
        let html=template('Choice',data);
        $("#"+selectionID).append(html);
    }
    function addEssay() {
        essayCount++;
        countID++;
        let q_type = countID + "-2";
        let data = {
            essayCount:essayCount,
            q_type:q_type
        };
        let html=template('essay',data);
        console.log(html);
        $("#addEssay").append(html);
    }
    function deleteQuestion(e) {
        let child = e.parentElement.parentElement;
        let type = child.className;
        let parent = child.parentElement;
        parent.removeChild(child);      //删除节点
        //修正题目标号
        switch (type) {
            case "single":
                singleCount--;
                break;
            case "multiple":
                multipleCount--;
                break;
            case "essay":
                essayCount--;
                break;
        }
        reCount(type+"Count");          //type+Count标签显示当前是第几题
        alert("删除题目成功");
    }
    //配合删除，修改题目号
    function reCount(className) {
        let arr = document.getElementsByClassName(className);
        for(let i=0;i<arr.length;i++){
            let num = i+1;
            arr[i].innerHTML = "第"+num+"题";
        }
    }
    var DomType = 0;        //1为单选，2为多选，3为问答
    //所有内容存放在一个数组里，通过JSON编码，以POST方式提交
    function submitAll() {
        const q_name = document.getElementById("q_name").value;
        const q_describe = document.getElementById("q_describe").value;
        let questionnaire = [];
        questionnaire[0] = [];
        questionnaire[0][0] = q_name;
        questionnaire[0][1] = q_describe;
        for(let i = 1; i<=countID;i++){
            questionnaire[i] = [];
            let type = getDOM(i);
            if(type!=="9"){
                questionnaire[i][0] = type;
                let string = i+"-"+type;
                questionnaire[i][1] = $("."+string)[0].value;       //题目信息
                let arr = $("."+i+"-selection");
                for(let j = 0;j<arr.length;j++){                //循环写入选项内容
                    questionnaire[i][j+2] = arr[j].value;
                }
            }
        }
        // for(let i = 0;i<questionnaire.length;i++){
        //     for(let j = 0;j<questionnaire[i].length;j++){
        //         let string =i +"+"+j+":"+ questionnaire[i][j];
        //         alert(string);
        //     }
        // }
        let data = JSON.stringify(questionnaire);
        ajaxPost(data);
    }
    function getDOM(id) {
        let single = document.getElementsByClassName(id+"-0");
        let multiple = document.getElementsByClassName(id+"-1");
        let essay = document.getElementsByClassName(id+"-2");
        if(isExist(single))return "0";
        if(isExist(multiple))return "1";
        if(isExist(essay))return "2";
        return "9";
    }
    function isExist(e){    //判断节点是否存在
        return typeof e !== 'undefined' && e.length >= 1;
    }
    var xmlHttp;
    function ajaxPost(message){
        S_xmlhttprequest();
        xmlHttp.open("POST","../DatabaseController/addQuestion",true);//找开请求
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
                alert(xmlHttp.responseText);
            }
        }
    }
</script>