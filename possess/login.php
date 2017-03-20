<?php
/**
 * 登录功能
 */
session_start();
require('./mysql.php');
@$_SESSION['ID'] = $_POST['ID'];
@$_SESSION['passwd'] = $_POST['passwd'];
$userID = $_SESSION['ID'];
$passwd = $_SESSION['passwd'];
$sql_tch = "select * from tch where passwd='$passwd' and tchID='$userID';";
$sql_admin = "select * from admin where passwd='$passwd' and adminID='$userID';";
$tch_result = mysqli_query($con, $sql_tch);
$admin_result = mysqli_query($con, $sql_admin);
$tch_row = mysqli_num_rows($tch_result);       /*查找是否有符合的用户*/
$admin_row = mysqli_num_rows($admin_result);         /*查找是否有符合的用户*/
if ($tch_row == 0 && $admin_row == 0) {
    echo "<br/><br/>您已注销或登录失败<br/><br/>点此<a href='../index.php'>重新登录</a><br />";
} else {
    if ($admin_row != 0) {
        header("location:../admin/admin.php");
    } else if ($tch_row != 0) {
        header("location:../tch/teacher.php");
    }
}