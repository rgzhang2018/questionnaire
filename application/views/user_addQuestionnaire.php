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
    <div class="am-u-md-8 am-u-md-centered">



        <form  action="#" method="post" class="am-form am-form-horizontal">
            <div class="am-form-group" style="text-align:center">
                <h2>请输入问卷信息</h2>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="col-sm-2 am-form-label">标题</label>
                <div class="col-sm-10">
                    <input type="text" id="doc-ipt-3" placeholder="输入问卷标题">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-pwd-2" class="col-sm-2 am-form-label">描述</label>
                <div class="col-sm-10">
                    <textarea id="doc-ta-1" placeholder="描述一下你的问卷吧" rows="4"></textarea>
                </div>
            </div>

            <div class="am-form-group">
                <div class="am-u-sm-4">
                    <button type="button" class="am-u-sm-9 am-btn am-btn-primary  am-round" onclick="addSingle()">添加单选</button>
                </div>
                <div class="am-u-sm-4">
                    <button type="button" class="am-u-sm-9 am-u-sm-centered am-btn am-btn-secondary  am-round">添加多选</button>
                </div>

                <div class="am-u-sm-4">
                    <button type="button" class="am-u-sm-9 am-u-sm-centered am-btn am-btn-secondary  am-round">添加问答</button>
                </div>
            </div>

            <div class="am-form-group"><br></div>

            <div class="am-form-group">
                <div class="am-u-sm-6">
                    <button type="submit" name="commit" class="am-btn am-btn-default am-btn-block" >完成提交</button>
                </div>
                <div class="am-u-sm-6">
                    <button class="am-btn am-btn-default am-btn-block" >重置问卷</button>
                </div>
            </div>
            <div class="am-form-group" >
                <hr>
                <h3>下面是题目预览</h3>
            </div>
            <div class="am-form-group" id="addItemSingle">
            </div>
        </form>
    </div>

</div>



<!--用于存放模版-->
<script type="text/javascript" src="../../assets/template-web.js"></script>


<!--下面是单选题目的添加-->
<script id="Question" type="text/html">
    <section class="am-panel am-panel-default">
        <header class="am-panel-hd">
            <h3 class="am-panel-title">
                <label><input type="text" placeholder="输入单选题题目"></label>
                <button type="button" class = "am-fr am-btn am-btn-default am-round" id=<%=id%> onclick="addSingleChoice(this)">添加选项</button>
            </h3>

        </header>
        <div class="am-panel-bd" id=<%=selection%> >
            <label><input type="text" placeholder="输入选项内容"></label><br>
            <label><input type="text" placeholder="输入选项内容"></label><br>
        </div>
    </section>
</script>

<!--下面是单选题选项的添加-->
<script id="Choice" type="text/html">
    <label><input type="text" name="" placeholder="输入选项内容"></label><br>
</script>




<!--用于渲染模版-->
<script>
    var signleID = 0;   //动态添加单选信息

    function addSingle() {
        signleID++;
        var Selection = signleID+"-singleChoice";
        var data = {
            id:signleID,
            selection:Selection
        };
        var html=template('Question',data);
        console.log(html);
        $("#addItemSingle").append(html);
    }

    function addSingleChoice(e){
        var selectionID = e.id +"-singleChoice";
        var data = {
        };
        var html=template('Choice',data);
        $("#"+selectionID).append(html);
    }

    function addMultiple() {
        signleID++;
        var Selection = signleID+"-multipleChoice";
        var data = {
            id:signleID,
            selection:Selection
        };
        var html=template('Question',data);
        console.log(html);
        $("#addItemMultiple").append(html);
    }
    function addMultipleChoice(e){
        var selectionID = e.id +"-multipleChoice";
        var data = {
        };
        var html=template('Choice',data);
        $("#"+selectionID).append(html);
        alert(selectionID);
    }
</script>

