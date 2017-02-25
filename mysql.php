<?php
/**
 * 连接数据库
 */
header("content-type:text/html;charset=utf-8");
$host = "localhost";
/*本地服务器，以后再换*/
$username = "root";
$password = "";
$db = "innovation";
$con = mysqli_connect($host, $username, $password, $db);
if (mysqli_connect_errno()) {
    echo "连接失败";
    exit();
}
