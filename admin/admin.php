<?php
/**
 *管理员操作界面
 */

?>
<?php
require('../possess/mysql.php');
require('../function/function.php');
session_start();
@$_SESSION["status"] = "admin";//将用户身份赋值为管理员
if ($_SESSION['ID'] == false) {
    header("location:../possess/login.php");
}
$adminID = $_SESSION['ID'];
$date = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from the_first_day'));
$firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];//第一天的日期格式化
$days = calDays($firstDay, $date);      /*当天和本学期第一天中间隔了多少天*/
$whichWeek = whichWeek($days);          /*当前是第几周*/
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理员操作界面</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<div>
    <?php
    $result = mysqli_query($con, "select * from messages");
    if (mysqli_affected_rows($con) > 0) {
        $message = mysqli_fetch_array($result)["message"];
        if ($message != "") {
            echo "<marquee style='font-size: 24px;color: black'>通知：" . $message . "</marquee>";
        }
    }
    ?>
</div>
<div align="left" class="main">
    <div>
        <?php

        echo "<p style='display: inline'>
                <span style='font-weight: bold;font-size: 110%'>" . $adminID . "</span> 管理员, 您好。
              </p>";
        ?>
        <div class="btn-group" style="display: inline-block;">
            <button class="btn btn-danger" onclick='window.location.href="../possess/logout.php"'>
                点此注销
            </button>
            <!--如果不使用密码登录方式，则注释下面的button标签-->
            <button class="btn btn-primary"
                    onclick='window.location.href="../possess/reset-password.php"'>点此修改密码
            </button>
        </div>
    </div>
    <p style="margin-top: 10px">今天是 第 <span
                class="todayIs"> <?php echo $whichWeek ?></span><?php echo " 周 <span class='todayIs'>周" . $day[date("w")] . '</span> ;' ?>
    </p>
    <ul class="list-group">
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='../tch/tch.php'">
                查看您当前有课/可直接控制的教室
            </button>
        </li>
        <br>
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='admin-query-log.php'">
                查看指定老师的操作记录
            </button>
        </li>
        <br>
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='admin-query-class.php'">
                查看/删除指定老师的今日机房课程
            </button>
        </li>
        <br>
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='admin-setDate.php'">
                设置本学期的第一天
            </button>
        </li>
        <br>
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='admin-setClass.php'">
                给老师开放临时的教室控制权限(限当天设置当天有效)
            </button>
        </li>
        <br>
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='admin-setMessages.php'">
                设置全员通知消息
            </button>
        </li>
    </ul>
</div>

<div class="bottom-remind">
<pre>
<span>注意：</span>
1.每周从周一开始计算;
2.若当前周次为负数(如-2),则为开学之前的第2周（倒数）；没有第0周;
</pre>
</div>

</body>
</html>