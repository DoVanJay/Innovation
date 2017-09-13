<?php
/**
 * 设置网络状态
 */
session_start();
require "../possess/mysql.php";
require "../possess/control_switch.php";
header("content-type:text/html;charset=utf-8");

//ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
//set_time_limit(0);//设置不受不响应最长时间限制

function clean_schedule($con, $classroomIp)
{
    $query_schedule = "select id from restore_network_schedule where classroom_ip='" . $classroomIp . "';";
    $result = mysqli_query($con, $query_schedule);
    if (mysqli_affected_rows($con) > 0) {
        $schedule_id = mysqli_fetch_array($result)[0]['id'];
        $delete_schedule = "delete from restore_network_schedule where id=" . $schedule_id . ";";
        mysqli_query($con, $delete_schedule);
    }
}

$operation_result = 000;

if ($_POST['network'] == 0 || $_POST['network'] == 1 || $_POST['network'] == 2) {
    $network = $_POST["network"];
    $classroomName = $_POST["classroomName"];
    $endTimestamp = $_POST["endTimestamp"];
    //查询教室ip和交换机ip
    $query_ip = "select classroom_ip,switch_ip from classroom_info where classroom_name='" . $classroomName . "';";
    $result = mysqli_query($con, $query_ip);
    $classroomIp = mysqli_fetch_array($result)[0]['classroom_ip'];
    $switchIp = mysqli_fetch_array($result)[0]['switch_ip'];
    switch ($network) {
        case 0:
            //这里设置交换机对应接口下的网络状态:完全开放
//            telnetExeCommand($host, $password, "undo packet-filter name dmt101_deny_upc inbound");

            //因为这是开放网络操作，所以要检查当前教室是否已经有计划任务，有的话需要取消
            clean_schedule($con, $classroomIp);

            $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","' . $_POST['classroomName'] . '","完全开放")';
            mysqli_query($con, $log_sql);
            $operation_result = 111;
            break;
        case 1:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态:仅关闭外网//////////////////////
//            telnetExeCommand();

            //将恢复网络计划任务加入恢复网络计划数据表中
            $query_ip = "select classroom_ip,switch_ip from classroom_info where classroom_name='" . $classroomName . "';";
            $result = mysqli_query($con, $query_ip);
            $classroomIp = mysqli_fetch_array($result)[0]['classroom_ip'];
            $switchIp = mysqli_fetch_array($result)[0]['switch_ip'];
            $store_schedule = "insert into restore_network_schedule(classroom_ip,switch_ip,endTimestamp) 
                              VALUES ('" . $classroomIp . "','" . $switchIp . "','" . $endTimestamp . "')";
            mysqli_query($con, $store_schedule);

            //将操作记录写入操作记录数据表中
            $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","' . $_POST['classroomName'] . '","仅关闭外网")';
            mysqli_query($con, $log_sql);
            $operation_result = 111;
            break;

        case 2:
            ///这里设置交换机对应接口下的网络状态:完全关闭//////////////////////
//            telnetExeCommand("packet-filter name dmt101_deny_upc inbound");

            //将恢复网络计划任务加入恢复网络计划数据表中

            $store_schedule = "insert into restore_network_schedule(classroom_ip,switch_ip,endTimestamp) 
                              VALUES ('" . $classroomIp . "','" . $switchIp . "','" . $endTimestamp . "')";
            mysqli_query($con, $store_schedule);


            $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","' . $_POST['classroomName'] . '","完全关闭")';
            mysqli_query($con, $log_sql);
            $operation_result = 111;
            break;
    }
}
header("location:tch.php?operation_result=" . $operation_result);