<?php
/**
 * 连接本地数据库
 */

require dirname(__FILE__) . "/../config/config.php";
$con = mysqli_connect($localhost, $local_db_user_name, $local_db_user_password, $local_db_name);
if (mysqli_connect_errno()) {
    if (mysqli_connect_errno() == 1049) {
        die("本地control_system数据库未创建，请在当前文件夹运行命令‘php create-database.php’ ");
    }
    die("连接失败");
}