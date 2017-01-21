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
//$username = $_POST['name'];
//$passwd = $_POST['passwd'];

$username = '杜文杰';
$passwd = 'dwjdwjdwj';
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
    if ($firstDay[0] < 10) {
        $firstDay[0] = "0" . '' . (string)$firstDay[0];
    }
    $firstDay = date('y') . '-' . $firstDay[0] . '-' . (string)$firstDay[1];
    $days = calDays($firstDay, $date);      /*当天和本学期第一天中间隔了多少天*/
    $whichweek = whichWeek($days);          /*当前是第几周*/
    $mysqlZC = $day[date("w")] . "0%";
    $sql_innovation = "select * from innovation WHERE JSXM='$username' AND find_in_set('$whichweek',SKZCMX) AND SKSJ like '1%' ";

    $result = mysqli_query($con, $sql_innovation);


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
    <p>今天是 第<span
            style="text-decoration-line: underline"> <?php echo "&nbsp" . $whichweek . " " ?></span><?php echo "周 周" ?>
        <span style="text-decoration-line: underline"> <?php echo $day[date("w")] . '&nbsp' ?></span>
    </p>
    <p id="p1">您今天 <span style="text-decoration-line: underline">
            <?php
            if (mysqli_num_rows($result)) {
                $n = mysqli_num_rows($result);
                while ($n > 0) {
                    $info = mysqli_fetch_array($result);            /*取出当前老师课表信息*/
                    $SKSJ = (string)$info[0];                       /*取出上课时间*/
                    $sksjPY = exec("python dayInWeek.py $SKSJ");    /*对上课时间进行切片*/
                    $info[1] = "第" . $sksjPY[1] . $sksjPY[2] . "节和" . "第" . $sksjPY[3] . $sksjPY[4] . "节  " . $info[1] . " 有课";
                    if ($n != mysqli_num_rows($result)) {
                        echo $info[1].";"."&nbsp;&nbsp;";
                    } else {
                        echo $info[1].";"."&nbsp;&nbsp;";
                    }
                    $n = $n - 1;
                }

//        $SKZCMX = $info[4];                             /*取出上课周次明细*/
//        $skzcmxPY = exec("python dayInWeek.py $SKZCMX");/*对上课周次进行切片*/
//        $lessonNum = (strlen($sksjPY)) / 5;             /*算出一周有几节课*/
//        $weekNum = (strlen($skzcmxPY) - 1) / 2;         /*算出几周有课*/
            } else {
                $info[1] = "无课";
                echo $info[1];
            }
            ?> </span></p>
    </p></body>
    </html>
    <?php
}
?>