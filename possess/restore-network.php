<?php
/**
 * 下课后自动恢复网络状态为开放外网
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/6/2017
 * Time: 11:21 PM
 */

class restore_network
{
    //定时任务
    function keepWake($flagFile, $endTimestamp)
    {
        ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
        set_time_limit(0);
        // 通过set_time_limit(0)可以让程序无限制的执行下去
        while (time() < $endTimestamp) {
            // 定时任务终止条件:本任务的flag文件不存在
            if (!file_exists("./flags/" . $flagFile)) {
                die('process terminated');
            }
            sleep(120);
        }
        $this->restoreNet();
    }

    //恢复网络状态函数
    function restoreNet()
    {

    }
}


