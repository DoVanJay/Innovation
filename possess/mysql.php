<?php
/**
 * 连接本地数据库
 */

//要改
$host = "localhost";
$username = "root";
$password = "";
$db = "control_system";
$con = mysqli_connect($host, $username, $password, $db);
if (mysqli_connect_errno()) {
    die("连接失败");
}