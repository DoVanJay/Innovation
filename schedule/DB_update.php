<?php
/**
 * 用以从主服务器更新课表数据
 * 需要将该任务文件写入服务器的计划任务中，每日凌晨主动运行一次
 * linux系统中如果该文件的绝对路径为/innovation/possess/Db_update.php
 * 则将"0 0 * * * php /innovation/possess/Db_update.php"加入/etc/crontab文件末尾
 *
 * 更新日志保存在同级目录下的DB_update_log.log文件中
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/4/2017
 * Time: 11:51 AM
 */

require dirname(__FILE__) . "/../possess/mysql.php";
require dirname(__FILE__) . "/../config/config.php";

//获取远程课程数据表中数据
$getDate_sql = "select $remote_tchID_colName,
                        $remote_timeForClass_colName,
                        $remote_locationOfClass_colName,
                        $remote_detailsOfWeeks_colName from $remote_table_name";


//连接远程数据库获取课程表数据,oracle为例
$connection = oci_connect($remote_db_user_name, $remote_db_user_password, $remote_db_host . "/" . $remote_db_name);
if ($connection) {
    $parse = oci_parse($connection, $getDate_sql);
    oci_execute($parse);
    $numOfRows = oci_fetch_all($parse, $results);
    if ($numOfRows > 0) {
        foreach ($result as $item) {
            //输出测试
            //echo $item[0] . "  " . $item[1] . "  " . $item[2] . "  " . $item[0] . "\n";
            //清空课表数据表
            $truncate_sql = "truncate course_timetable;";
            mysqli_query($con, $truncate_sql);
            $insert_sql = "INSERT into 
                `course_timetable`(tchID,timeForClass,locationOfClass,detailsOfWeeks) 
                values ($item[0],$item[1],$item[2],$item[3])";
            $insert_exe = mysqli_query($con, $insert_sql);
            if ($insert_exe) {
                $content = date("Y/m/d h:i:sa") . "  课表数据更新成功\n";
            } else {
                $content = date("Y/m/d h:i:sa") . "  课表数据更新失败\n";
            }
            //将更新日志写入文件
            file_put_contents("./DB_update_log.log", $content, FILE_APPEND);
        }
    }
}
