<?php
/**
 * SINGLE SIGN ON，单点登录文件
 *
 * 如果只使用单点登录的方式登录则请将index.php重命名为backup.php，并将该文件命名为index.php,
 *
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/10/2017
 * Time: 10:12 AM
 */

require "./possess/mysql.php";
session_start();

//用户自己实现验证函数
function toVerify()
{
    if ("通过验证")
        return true;
    else
        return false;
}

//由于不同应用系统实现机制不一样，具体的单点登录由使用方实现

//如果需要验证函数则自己实现
$isVerify = toVerify();

//如果通过验证
if ($isVerify == true) {
    //通过身份验证之后，必须将用户id信息写入session，即传给$_SESSION["ID"]变量
    $_SESSION['ID'] = "";

    //根据$_SESSION["ID"]的值来查询身份并跳转到对应的操作界面
    $sql_tch = "select tchID from tch where tchID='" . $_SESSION['ID'] . "';";
    $tch_result = mysqli_fetch_all(mysqli_query($local_con, $sql_tch));
    if ($tch_result) {
        $_SESSION["status"] = "tch";
        header("location:./tch/tch.php");
    }

    $sql_admin = "select adminID from admin where adminID='" . $_SESSION['ID'] . "';";
    $admin_result = mysqli_fetch_all(mysqli_query($local_con, $sql_admin));
    if ($admin_result) {
        $_SESSION["status"] = "admin";
        header("location:./admin/admin.php");
    }
} else {
    //未通过验证则跳转至logout.php
    header("location:./possess/logout.php");
}

