<?php
/**
 * 下课后自动恢复网络状态为开放外网
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/6/2017
 * Time: 11:21 PM
 */
require dirname(__FILE__) . "/../possess/mysql.php";
require dirname(__FILE__) . "/../possess/control_switch.php";

//恢复网络状态函数
function restoreNet($con, $vlan, $acl_num, $switchIp)
{
    $query_switch_passwd = "select passwd from switch_info where switch_ip='" . $switchIp . "';";
    $switch_passwd = mysqli_fetch_array(mysqli_query($con, $query_switch_passwd))['passwd'];
    $command = [
        "interface Vlan-interface " . $vlan,
        "undo packet-filter " . $acl_num . " inbound"
    ];
    telnetExeCommand($switchIp, $switch_passwd, $command);
}

function update_current_acl($con, $classroom_vlan)
{
    $update_sql = "UPDATE `classroom_info` SET `current_acl_num` = '#' WHERE vlan ='" . $classroom_vlan . "';";
    mysqli_query($con, $update_sql);
}

//查询恢复网络计划表中的任务
$query_schedule_sql = "select * from restore_network_schedule;";
$result = mysqli_query($local_con, $query_schedule_sql);
$schedule_task = mysqli_fetch_all($result);
if (is_array($schedule_task))
{
    foreach ($schedule_task as $task) {
        if ($task[4] < time()) {
            restoreNet($local_con, $task[1], $task[2], $task[3]);
            update_current_acl($local_con, $task[1]);
            //执行完恢复网络任务后，删除数据表中的原有计划任务
            $delete_schedule_sql = "delete from restore_network_schedule where id='" . $task[0] . "';";
            mysqli_query($local_con, $delete_schedule_sql);
        }
    }
}