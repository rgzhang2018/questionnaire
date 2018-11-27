<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 13:17
 */

?>


<style type="text/css">

    #bg_success{
        background: url(../../assets/myImg/newSuccess.jpg);
        box-sizing: border-box;
        max-width: 100%;
        height: 700px;
        vertical-align: middle;
        border: 0;
    }

</style>

<div class="am-u-md-12" id="bg_success" >

<h1>SUCCESS!</h1>
<p id="001"><?php   if(isset($controlMessage))echo $controlMessage;    ?>,即将转跳
</p>

<?php
    if(isset($nextURL)){
        header('refresh:2; url='.$nextURL);
    }

?>
</div>
<script type="javascript">
    <?php
        if(!isset($nextURL)){
            echo "alert('操作成功！');";
            echo "window.history.back(-1);";
        }
    ?>
</script>
