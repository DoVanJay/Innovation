<?php
/**
 * 密码登录功能
 */
require('./mysql.php');
session_start();
if (@$_SESSION["status"] == null) {
    //说明是从单点登录过来的
    /**
     * $_SESSION['ID']的值应该在单点登录处理的页面被赋值为用户id，
     * 这样此处的$userID才能取到有效值
     */
    $userID = $_SESSION['ID'];
    $sql_tch = "select tchID from tch where tchID='$userID';";
    $tch_result = mysqli_query($local_con, $sql_tch);
    $row_result = mysqli_num_rows($tch_result);
    if ($row_result > 0) {
        $_SESSION["status"] = "tch";
    } else {
        $_SESSION["status"] = "admin";
    }
}

if ($_SESSION["status"] == "tch") {
    header("location:../tch/tch.php");
} elseif ($_SESSION["status"] == "admin") {
    header("location:../admin/admin.php");
}

