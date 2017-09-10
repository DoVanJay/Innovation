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

require "../possess/mysql.php";
/**
 * 远程数据库配置
 */
//远程服务器地址
$remote_db_host = "";
//远程数据库用户名
$remote_db_user_name = "";
//远程数据库用户密码
$remote_db_user_password = "";
//远程课程数据表所在的数据库名
$remote_db_name = "";
//远程课程数据表名
$remote_table_name = "";

//下面定义远程课程数据表中的各项字段名
//教师工号字段
$remote_tchID_colName = "";
//上课时间（第几节课）字段
$remote_timeForClass_colName = "";
//上课地点字段
$remote_locationOfClass_colName = "";
//上课周次（第几周有课）字段
$remote_detailOfWeeks_colName = "";

/**
 * 本地数据库配置
 */
//本地课程数据表名
$local_table_name = "";

//下面定义本地课程数据表中的各项字段名
//教师工号字段
$local_tchID_colName = "";
//上课时间（第几节课）字段
$local_timeForClass_colName = "";
//上课地点字段
$local_locationOfClass_colName = "";
//上课周次（第几周有课）字段
$local_detailOfWeeks_colName = "";


//获取远程课程数据表中数据
$getDate_sql = "select $remote_tchID_colName,
                        $remote_timeForClass_colName,
                        $remote_locationOfClass_colName,
                        $remote_detailOfWeeks_colName from $table_name";


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
            $truncate_sql = "truncate" . $local_table_name . ";";
            mysqli_query($con, $truncate_sql);
            $insert_sql = "INSERT into 
                `$local_table_name`($local_tchID_colName,$local_timeForClass_colName,$local_locationOfClass_colName,$local_detailOfWeeks_colName) 
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
