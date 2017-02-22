<?php
session_start();
header("content-type:text/html;charset=utf-8");
$host = "localhost";
$username = "root";
$password = "";
$db = "operating_log";
$con = mysqli_connect($host, $username, $password, $db);
if (mysqli_connect_errno()) {
    echo "连接失败";
    exit();
}
//$result = mysqli_query("SELECT * FROM log WHERE NAME =".$_SESSION["username"]);
$sql = "insert log values(NOW(),".$_SESSION["username"].$_SESSION;
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
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
header("location:teacher.php?setnetwork=" . $var);/*get传参并不安全，还要解决注销之后记录当前网络状态的问题*/
