<?php
/**
 * SINGLE SIGN ON
 * 单点登录文件
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/10/2017
 * Time: 10:12 AM
 */

require "./possess/mysql.php";
session_start();


//由于不同应用系统实现机制不一样，具体的单点登录由使用方实现
//
//
//
//
//在实现身份验证之后，必须将用户id信息写入session，即传给$_SESSION["ID"]变量
//根据$_SESSION["ID"]的值来查询身份并跳转到对应的操作界面
$sql_tch = "select tchID from tch where tchID='" . $_SESSION['ID'] . "';";
$tch_result = mysqli_fetch_all(mysqli_query($con, $sql_tch));
if ($tch_result) {
    $_SESSION["status"] = "tch";
    header("location:./possess/login.php");
}

$sql_admin = "select adminID from admin where adminID='" . $_SESSION['ID'] . "';";
$admin_result = mysqli_fetch_all(mysqli_query($con, $sql_admin));
if ($admin_result) {
    $_SESSION["status"] = "admin";
    header("location:./possess/login.php");
}




