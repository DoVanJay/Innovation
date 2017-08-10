<?php
/**
 *管理员操作界面
 */
function calDays($date1, $date2)        /*计算两天之间隔了多少天*/
{
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    return (($time2 - $time1) / 86400 - 1);
}

function whichWeek($days)               /*计算当前是第几周*/
{
    if (($days / 7) >= floor($days / 7)) {
        return floor($days / 7) + 1;
    } else {
        return (int)($days / 7);
    }
}

?>
<?php
require('../possess/mysql.php');
session_start();
@$_SESSION["status"] = "admin";//将用户身份赋值为管理员
if ($_SESSION['ID'] == false) {
    header("location:../possess/login.php");
}
$adminID = $_SESSION['ID'];
$date = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from TheFirstDay'));
$firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];//第一天的日期格式化
$days = calDays($firstDay, $date);      /*当天和本学期第一天中间隔了多少天*/
$whichWeek = whichWeek($days);          /*当前是第几周*/
if (date("w") != 0) {
    $mysqlZJ = date("w") . "%";
} else {
    $mysqlZJ = 7 . "%";
}
$sql_innovation = "select * from innovation 
                      WHERE JSXM='$username' 
                      AND find_in_set('$whichWeek',SKZCMX) 
                      AND SKSJ like '$mysqlZJ' ";
$result = mysqli_query($con, $sql_innovation);
?>
<html>
<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理员操作界面</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div align="center"><img src="/imgs/title.png" width="550"/>
</div>
<div align="left" class="main">
    <div>
        <?php
        echo "<p style='display: inline'><span style='font-weight: bold;font-size: 110%'>" . $adminID . "</span> 管理员, 您好。</p>";
        ?>
        <div class="btn-group" style="display: inline-block;">
            <button class="btn btn-danger" onclick='window.location.href="../possess/logout.php"'>
                点此注销
            </button>
            <button class="btn btn-primary"
                    onclick='window.location.href="../possess/reset-password.php"'>点此修改密码
            </button>
        </div>
    </div>
    <p>今天是 第 <span
                class="todayIs"> <?php echo $whichWeek ?></span><?php echo " 周 <span class='todayIs'>周" . $day[date("w")] . '</span> ;' ?>
    </p>
    <ul class="list-group">
        <li>
            <button class="btn btn-primary" onclick=" window.location.href='../tch/teacher.php'">
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
    </ul>
</div>

<div class="bottom-remind">
<pre>
<span>注意：</span>
1.每周从周一开始计算;
2.若当前周次为负数(如-2),则为开学前倒数第2周;
</pre>
</div>

</body>
</html>