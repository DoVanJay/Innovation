<?php
/**
 *管理员操作界面
 */
/*自定义函数*/
function calDays($date1, $date2)        /*计算两天之间隔了多少天*/
{
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    return ($time2 - $time1) / 86400;
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
if ($_SESSION['username'] == false) {
    header("location:login.php");
}
$username = $_SESSION['username'];
$date = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from TheFirstDay'));
if ($firstDay[0] < 10) {
    $firstDay[0] = "0" . '' . (string)$firstDay[0];
}
$firstDay = date('y') . '-' . $firstDay[0] . '-' . (string)$firstDay[1];
$days = calDays($firstDay, $date);      /*当天和本学期第一天中间隔了多少天*/
$whichweek = whichWeek($days);          /*当前是第几周*/
if (date("w") != 0) {
    $mysqlZJ = date("w") . "%";
} else {
    $mysqlZJ = 7 . "%";
}
$sql_innovation = "select * from innovation WHERE JSXM='$username' AND find_in_set('$whichweek',SKZCMX) AND SKSJ like '$mysqlZJ' ";
$result = mysqli_query($con, $sql_innovation);
?>
<html>
<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理系统</title>
</head>
<body>
<div align="center"><img src="../head.jpg" width="550"/>
</div>
<br/><br/>
<?php
echo "<p><span style='font-weight: bold;font-size: 110%'>".$username . "</span> 管理员, 您好。</p>";
echo '点此 <a href="../possess/off.php">注销</a>';
?>
<p>今天是 第<span
        style="text-decoration-line: underline"> <?php echo "&nbsp" . $whichweek . " " ?></span><?php echo "周 周" . $day[date("w")] . '&nbsp;&nbsp;;' ?></span>
</p>
<p>点此 <a href="admin-query.php">查看所有老师的操作记录</a></p>
<p>点此 <a href="admin-setDate.php">设置本学期的第一天以及节假日</a></p>
<p>点此<a href="admin-setClass.php">给老师开放临时的教室控制权限(限当天)</a></p>
<br/>
<hr/>
<p id="notice">
<pre style="font-size:120%">
<span style="color: red;font-weight: bold;font-size: 140%">注意：</span>
    <!--to be continue-->
    <!--TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC-->
    <!--管理员操作提示       -->
    <!--T:::::::::::::::::::::T         B::::::BBBBBB:::::B            CC:::::::::::::::C-->
    <!--TTTTTT  T:::::T  TTTTTT           B::::B     B:::::B          C:::::C       CCCCCC-->
    <!--        T:::::T                   B::::B     B:::::B         C:::::C-->
    <!--        T:::::T                   B::::BBBBBB:::::B         C:::::C-->
    <!--        T:::::T                   B:::::::::::::BB          C:::::C-->
    <!--        T:::::T                   B::::BBBBBB:::::B         C:::::C-->
    <!--        T:::::T                   B::::B     B:::::B        C:::::C-->
    <!--        T:::::T                   B::::B     B:::::B        C:::::C-->
    <!--        T:::::T                   B::::B     B:::::B         C:::::C       CCCCCC-->
    <!--      TT:::::::TT               BB:::::BBBBBB::::::B          C:::::CCCCCCCC::::C-->
    <!--      T:::::::::T               B:::::::::::::::::B            CC:::::::::::::::C-->
    <!--      T:::::::::T               B::::::::::::::::B               CCC::::::::::::C-->
    <!--      TTTTTTTTTTT               BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC-->
    </pre>
</p></div>
</body>
</html>