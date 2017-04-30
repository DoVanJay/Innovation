<?php
/**
 * 设置网络状态
 */
session_start();
include("../test.php");
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
$_SESSION["tchID"] = '1507020326';//临时设置
if ($_POST['network'] != 0 && $_POST['network'] != 1 && $_POST['network'] != 2) {
    echo "<script>alert('对不起,操作出错！')</script>";
} else {
    $var = $_POST["network"];
    switch ($_POST['network']) {
        case 0:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态:完全开放////////////////////
            exe("undo packet-filter name dmt101_deny_upc inbound");
            $sql = "insert log values(NOW(),\"" . $_SESSION["tchID"] . $_SESSION["username"] . "\",\"" . $_POST['classroomName'] . "\",\"完全开放\")";
            mysqli_query($con, $sql);
            break;
        case 1:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态:仅关闭外网//////////////////////
            ///////////////////////////////////////////////////
            $sql = "insert log values(NOW(),\"" . $_SESSION["tchID"] . $_SESSION["username"] . "\",\"" . $_POST['classroomName'] . "\",\"仅关闭内网\")";
            mysqli_query($con, $sql);
            break;
        case 2:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态:完全关闭//////////////////////
            ///////////////////////////////////////////////////
            exe("packet-filter name dmt101_deny_upc inbound");
            $sql = "insert log values(NOW(),\"" . $_SESSION["tchID"] . $_SESSION["username"] . "\",\"" . $_POST['classroomName'] . "\",\"完全关闭\")";
            mysqli_query($con, $sql);
            break;
    }
}
header("location:teacher.php");