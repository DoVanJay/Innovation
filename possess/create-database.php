<?php
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/12/2017
 * Time: 3:44 PM
 */


require dirname(__FILE__) . "/../config/config.php";
//如果control_system数据库不存在则将新建
//注意新建的数据库中将
//在admin表中有一个adminID为“1507020325”，passwd为“yq”的测试账号
//在tch表中有一个tchID为“1507020326”，passwd为“dwj”的测试账号
//在the_first_day表中将初始化为year=2017，month=9，day=4，即默认开学时间为2017-9-4
//请注意修改或删除
$createCon = mysqli_connect($localhost, $local_db_user_name, $local_db_user_password);
mysqli_query($createCon, "create database " . $local_db_name . ";");
if ($local_db_user_password) {
    system("mysql -u" . $local_db_user_name . " -p" . $local_db_user_password . "  " . $local_db_name . " < ../config/control_system.sql ");
    echo "导入数据库成功";
} else {
    system("mysql -u " . $local_db_user_name . "  " . $local_db_name . " < ../config/control_system.sql ");
    echo "导入数据库成功";
}