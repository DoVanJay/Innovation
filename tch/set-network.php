<?php
/**
 * 设置网络状态
 */
session_start();
require "../possess/mysql.php";
require "../possess/control_switch.php";
require "../possess/restore-network.php";
header("content-type:text/html;charset=utf-8");

if ($_POST['network'] != 0 && $_POST['network'] != 1 && $_POST['network'] != 2) {
    echo "<script>alert('对不起,操作出错！')</script>";
} else {
    $endTimestamp = $_POST["endTimestamp"];
    echo $endTimestamp;
    $var = $_POST["network"];
    switch ($_POST['network']) {
        case 0:
            //这里设置交换机对应接口下的网络状态:完全开放
            telnetExeCommand($host, $password, "undo packet-filter name dmt101_deny_upc inbound");
            $log_sql = "insert operating_log values(NOW(),\"" . $_SESSION["ID"] . "\",\"" . $_POST['classroomName'] . "\",\"完全开放\")";
            mysqli_query($con, $log_sql);
            //查找当前用户有无已存在的flag，有的话删除，即终止已存在的恢复网络计划任务
            $search = glob("./possess/flags/" . $_SESSION['ID']);
            if ($search) {
                foreach ($search as $item) {
                    unlink($item);
                }
            }

            break;
        case 1:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态:仅关闭外网//////////////////////
            ///////////////////////////////////////////////////
            $log_sql = "insert operating_log values(NOW(),\"" . $_SESSION["ID"] . "\",\"" . $_POST['classroomName'] . "\",\"仅关闭内网\")";
            mysqli_query($con, $log_sql);
            //查找当前用户有无已存在的flag，有的话删除，即终止已存在的恢复网络计划任务
            $search = glob("./possess/flags/" . $_SESSION['ID']);
            if ($search) {
                foreach ($search as $item) {
                    unlink($item);
                }
            }
            $flag = $_SESSION["ID"] . time() . ".flag";
            $newFlag = fopen("../possess/flags/" . $flag, "w");
            fclose($newFlag);
            $schedule = new restore_network();
            $schedule->keepWake($flag, $endTimestamp);
            break;
        case 2:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态:完全关闭//////////////////////
            ///////////////////////////////////////////////////
            exe("packet-filter name dmt101_deny_upc inbound");
            $log_sql = "insert operating_log values(NOW(),\"" . $_SESSION["ID"] . "\",\"" . $_POST['classroomName'] . "\",\"完全关闭\")";
            mysqli_query($con, $log_sql);
            //查找当前用户有无已存在的flag，有的话删除，即终止已存在的恢复网络计划任务
            $search = glob("./possess/flags/" . $_SESSION['ID']);
            if ($search) {
                foreach ($search as $item) {
                    unlink($item);
                }
            }
            $flag = $_SESSION["ID"] . time() . ".flag";
            $newFlag = fopen("../possess/flags/" . $flag, "w");
            fclose($newFlag);
            $schedule = new restore_network();
            $schedule->keepWake($flag, $endTimestamp);
            break;
    }
}
//header("location:tch.php");