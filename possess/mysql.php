<?php
/**
 * 连接本地数据库
 */

//要改
$host = "localhost";
$username = "root";
$password = "D1ccb572";
$db = "innovation";
$con = mysqli_connect($host, $username, $password, $db);
if (mysqli_connect_errno()) {
    echo "连接失败";
    exit();
}