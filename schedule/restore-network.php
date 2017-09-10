<?php
/**
 * 下课后自动恢复网络状态为开放外网
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/6/2017
 * Time: 11:21 PM
 */

//恢复网络状态函数
function restoreNet($classroomIp, $switchIp)
{
    //连接交换机，恢复网络状态

}

require "../possess/mysql.php";
//查询恢复网络计划表中的任务
$query_schedule_sql = "select * from restore_network_schedule;";
$result = mysqli_query($con, $query_schedule_sql);
$schedule_task = mysqli_fetch_array($result);
foreach ($schedule_task as $task) {
    if ($task['endTimestamp'] < time()) {
        restoreNet($task['classroom_ip'], $task['switch_ip']);
        //执行完恢复网络任务后，删除数据表中的原有计划任务
        $delete_schedule_sql = "delete from restore_net_schedule where id='" . $task['id'] . "';";
        mysqli_query($con, $delete_schedule_sql);
    }
}
