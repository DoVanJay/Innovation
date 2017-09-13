<?php
/**
 * 设置网络状态
 */
session_start();
require "../possess/mysql.php";
require "../possess/control_switch.php";
require "../config/config.php";
header("content-type:text/html;charset=utf-8");

//ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
//set_time_limit(0);//设置不受不响应最长时间限制

//echo "current_acl= " . $_SESSION["current_acl"] . "\n";
//echo "vlan= " . $_SESSION['vlan'] . "\n";
//echo "switchIP= " . $_SESSION['SwitchIp'] . "\n";
//echo "switch passwd= " . $_SESSION['switch_passwd'];

function clean_schedule($con)
{
    $query_schedule = "select id from restore_network_schedule where classroom_vlan='" . $_SESSION["vlan"] . "';";
    $result = mysqli_query($con, $query_schedule);
    if (mysqli_affected_rows($con) > 0) {
        $schedule_id = mysqli_fetch_array($result)['id'];
        $delete_schedule = "delete from restore_network_schedule where id=" . $schedule_id . ";";
        mysqli_query($con, $delete_schedule);
    }
}

function update_current_acl($con, $set_acl_num, $classroomName)
{
    $update_sql = "UPDATE `classroom_info` SET `current_acl_num` = '" . $set_acl_num . "' WHERE classroom_name ='" . $classroomName . "';";
    mysqli_query($con, $update_sql);
}

//初始化$operation_result的值为操作失败的值
$operation_result = 000;
if ($_POST['network'] == 0 || $_POST['network'] == 1 || $_POST['network'] == 2) {
    $network = $_POST["network"];
    $classroomName = $_POST["classroomName"];
    echo $classroomName;
    $endTimestamp = $_POST["endTimestamp"];
    //查询教室ip和交换机ip
    $query_ip = "select vlan,switch_ip from classroom_info where classroom_name='" . $classroomName . "';";
    $result = mysqli_query($con, $query_ip);
    $classroomVlan = mysqli_fetch_array($result)['vlan'];
    $switchIp = mysqli_fetch_array($result)['switch_ip'];
    switch ($network) {
        case 0:
            //这里设置交换机对应接口下的网络状态:完全开放
            if ($_SESSION["current_acl"] != "open") {
                $command = [
                    "interface Vlan-interface " . $_SESSION["vlan"],
                    "undo packet-filter " . $_SESSION["current_acl"] . " inbound"
                ];
                telnetExeCommand($_SESSION['SwitchIp'], $_SESSION["switch_passwd"], $command);
            }

            //因为这是开放网络操作，所以要检查当前教室是否已经有计划任务，有的话需要取消
            clean_schedule($con);
            update_current_acl($con, $open_net_acl, $classroomName);
            $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","' . $_POST['classroomName'] . '","完全开放")';
            mysqli_query($con, $log_sql);
            $operation_result = 111;
            break;
        case 1:
            //这里设置交换机对应接口下的网络状态:仅关闭外网
            if ($_SESSION["current_acl"] == "open") {
                $command = [
                    "interface Vlan-interface " . $_SESSION["vlan"],
                    "packet-filter " . $only_campus_acl . " inbound"
                ];
            } else {
                $command = [
                    "interface Vlan-interface " . $_SESSION["vlan"],
                    "undo packet-filter " . $_SESSION["current_acl"] . " inbound",
                    "packet-filter " . $only_campus_acl . " inbound"
                ];
            }
            telnetExeCommand($_SESSION['SwitchIp'], $_SESSION["switch_passwd"], $command);
            clean_schedule($con);
            update_current_acl($con, $only_campus_acl, $classroomName);
            //将恢复网络计划任务加入恢复网络计划数据表中
            $query_ip = "select classroom_vlan,switch_ip from classroom_info where classroom_name='" . $classroomName . "';";
            $result = mysqli_query($con, $query_ip);
            $switchIp = mysqli_fetch_array($result)[0]['switch_ip'];
            $store_schedule = "insert into restore_network_schedule(classroom_vlan,current_acl_num,switch_ip,endTimestamp) 
                              VALUES ('" . $_SESSION["vlan"] . "','" . $only_campus_acl . "','" . $_SESSION['SwitchIp'] . "','" . $endTimestamp . "')";
            mysqli_query($con, $store_schedule);

            //将操作记录写入操作记录数据表中
            $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","' . $_POST['classroomName'] . '","仅关闭外网")';
            mysqli_query($con, $log_sql);
            $operation_result = 111;
            break;

        case 2:
            ///这里设置交换机对应接口下的网络状态:完全关闭
            if ($_SESSION["current_acl"] == "open") {
                $command = [
                    "interface Vlan-interface " . $_SESSION["vlan"],
                    "packet-filter " . $shutdown_net_acl . " inbound"
                ];
            } else {
                $command = [
                    "interface Vlan-interface " . $_SESSION["vlan"],
                    "undo packet-filter " . $_SESSION["current_acl"] . " inbound",
                    "packet-filter " . $shutdown_net_acl . " inbound"
                ];
            }
            telnetExeCommand($_SESSION['SwitchIp'], $_SESSION["switch_passwd"], $command);
            clean_schedule($con);
            update_current_acl($con, $shutdown_net_acl, $classroomName);
            //将恢复网络计划任务加入恢复网络计划数据表中
            $store_schedule = "insert into restore_network_schedule(classroom_vlan,current_acl_num,switch_ip,endTimestamp) 
                              VALUES ('" . $_SESSION['vlan'] . "','" . $shutdown_net_acl . "','" . $_SESSION['SwitchIp'] . "','" . $endTimestamp . "')";
            mysqli_query($con, $store_schedule);

            $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","' . $_POST['classroomName'] . '","完全关闭")';
            mysqli_query($con, $log_sql);
            $operation_result = 111;
            break;
    }
}
header("location:tch.php?operation_result=" . $operation_result);