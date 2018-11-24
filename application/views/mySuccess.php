<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/23
 * Time: 13:17
 */

?>



<h1>SUCCESS!</h1>
<p id="001"><?php   if(isset($controlMessage))echo $controlMessage;    ?>,即将转跳
</p>

<?php
    if(isset($nextURL)){
        header('refresh:2; url='.$nextURL);
    }

?>

<script>
    <?php
        if(!isset($nextURL)){
            echo "alert('操作成功！');";
            echo "window.history.back(-1);";
        }
    ?>
</script>
