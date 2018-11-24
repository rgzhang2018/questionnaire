<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/24
 * Time: 20:03
**/
?>


<h1>ERROR!</h1>
<p id="001"><?php   if(isset($controlMessage))echo $controlMessage;    ?>,现在将转跳至操作前的界面
</p>

<script>
    // window.location.href = document.referrer;
    var test = document.getElementById("001").innerText;
    alert(test);
    window.history.back(-1);
</script>