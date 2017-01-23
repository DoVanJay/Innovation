<?php
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 17-1-23
 * Time: 下午9:38
 */
session_start();
if ($_POST['network'] != 0 && $_POST['network'] != 1 && $_POST['network'] != 2) {
    echo "<script>alert('对不起,操作出错！')</script>";
} else {
    $var = $_POST["network"];
    switch ($_POST['network']) {
        case 0:
            echo "it's 0";
            break;
        case 1:
            echo "it's 1";
            break;
        case 2:
            echo "it's 2";
            break;
    }

}

header("location:teacher.php?setnetwork=".$var);/*get传参并不安全，还要解决注销之后记录当前网络状态的问题*/
