<?php /*自定义函数*/

function calDays($date1, $date2)        /*计算两天之间隔了多少天*/
{
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    return ($time2 - $time1) / 86400;
}

function whichWeek($days)               /*计算当前是第几周*/
{
    if (($days / 7) > floor($days / 7)) {
        return floor($days / 7) + 1;
    } else {
        return int($days / 7);
    }
}

?>

<?php
require('./mysql.php');
$username = $_POST['name'];
$passwd = $_POST['passwd'];
//session_start();
$_SESSION['s_username'] = $username;
$sql_user = "select * from user where passwd='$passwd' and name='$username';";
$result[] = mysqli_query($con, $sql_user);
$row = mysqli_fetch_row($result[0]);         /*查找是否有符合的用户*/
if ($row == 0) {
    echo "无法登录";
} else {
    $date = date('y-m-d');
    $day = array('日', '一', '二', '三', '四', '五', '六');
    $firstDay = mysqli_fetch_array(mysqli_query($con, "select * from TheFirstDay"));
    $dayInWeek = date("D");     /*获取当前周几*/
    if ($firstDay[0] < 10) {
        $firstDay[0] = "0" . '' . (string)$firstDay[0];
    }
    $firstDay = date('y') . '-' . $firstDay[0] . '-' . (string)$firstDay[1];
    $days = calDays($firstDay, $date); /*中间隔了多少天*/
    $whichweek = whichWeek($days);     /*当前是第几周*/

    $sql_innovation = "select * from innovation WHERE JSXM='$username'";
    $result = mysqli_query($con, $sql_innovation);
    $info = mysqli_fetch_array($result);            /*取出当前老师课表信息*/
    $SKSJ = (string)$info[0];                       /*取出上课时间*/
    $SKZCMX = $info[4];                             /*取出上课周次明细*/
    $sksjPY = exec("python dayInWeek.py $SKSJ");    /*对上课时间进行切片*/
    $skzcmxPY = exec("python dayInWeek.py $SKZCMX");/*对上课进行切片*/
    echo '<br/>';
    $lessonNum = (strlen($sksjPY)) / 5;             /*算出一周有几节课*/
    $weekNum = (strlen($skzcmxPY) - 1) / 2;         /*算出几周有课*/

    /*    先判断这是第几周
        再遍历对比skzcmxPY，确定这周是否有课
        再根据dayInWeek判断这是周几，
        再根据sksjPY中每隔5个就是周几有课来决定今天在哪有课*/

    ?>
    <html>
    <head>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>管理系统</title>
    </head>
    <body>
    <h1 style="text-align: center">多媒体机房管理系统</h1>
    <p>今天是 第<span style="text-decoration-line: underline"> <?php echo "&nbsp".$whichweek." "?></span><?php echo "周 周"?><span style="text-decoration-line: underline"> <?php echo $day[date("w")-1].'&nbsp' ?></span>
    </p>
    <p id="p1">您当前在 <span style="text-decoration-line: underline"><?php echo $info[1]; ?> </span>有课</php></p>
    </p></body>
    </html>
    <?php
}
?>