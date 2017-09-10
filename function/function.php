<?php
/**自定义函数
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 8/10/2017
 * Time: 10:35 PM
 */


function calDays($date1, $date2)        /*计算两天之间隔了多少天*/
{
    $time1 = strToTime($date1);
    $time2 = strToTime($date2);
    return ($time2 - $time1) / 86400;
}

function whichWeek($days)               /*计算当前是第几周*/
{
    if ($days < 0) {
        return floor($days / 7);
    } else {
        return floor($days / 7) + 1;
    }
}