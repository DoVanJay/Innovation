<!--
注意:
1.实际课表中的字段位置可能与现在不一致,拿到详细数据库后要进行调整;
2.
-->

<?php /*自定义函数*/

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
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 17-1-23
 * Time: 下午10:54
 */
session_start();
require('./mysql.php');
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
<h1 style="text-align: center">多媒体机房管理系统</h1>
<?php
echo $username . " 老师,您好。";
?>
<?php
echo '点此 <a href="off.php">注销</a><br />  点此查看您的操作记录<>';
?>
<p>今天是 第<span
        style="text-decoration-line: underline"> <?php echo "&nbsp" . $whichweek . " " ?></span><?php echo "周 周" ?>
    <span style="text-decoration-line: underline"> <?php echo $day[date("w")] . '&nbsp' ?></span>
</p>
<p id="p1">您今天 <span style="text-decoration-line: underline">
            <?php
            if (mysqli_num_rows($result)) {
                $n = mysqli_num_rows($result);
                $o = 0;
                while ($n > 0) {
                    $info = mysqli_fetch_array($result);            /*取出当前老师课表信息*/
                    $SKSJ = (string)$info[0];                       /*取出上课时间*/
                    $sksjPY = exec("python dayInWeek.py $SKSJ");    /*对上课时间进行切片*/
                    $m = strlen($sksjPY);
                    if (strpos($info[1], "多媒体") || strpos($info[1], "文理楼")) {
                        $operation[$o][0] = $info[0];
                        $operation[$o][1] = $info[1];
                        $o = $o + 1;
                    }
                    switch ($m) {
                        case "9":
                            $info[1] = "第" . $sksjPY[1] . $sksjPY[2] . "节/" . "第" . $sksjPY[3] . $sksjPY[4] . "节/" . "第" . $sksjPY[5] . $sksjPY[6] . "节/" . "第" . $sksjPY[7] . $sksjPY[8] . "节  " . $info[1] . " 有课";
                            break;
                        case "7":
                            $info[1] = "第" . $sksjPY[1] . $sksjPY[2] . "节/" . "第" . $sksjPY[3] . $sksjPY[4] . "节/" . "第" . $sksjPY[5] . $sksjPY[6] . "节  " . $info[1] . " 有课";
                            break;
                        default:
                            $info[1] = "第" . $sksjPY[1] . $sksjPY[2] . "节和" . "第" . $sksjPY[3] . $sksjPY[4] . "节  " . $info[1] . " 有课";
                    }

                    echo $info[1] . ";" . "&nbsp;&nbsp;";
                    $n = $n - 1;
                }

            } else {
                $info[1] = "无课";
                echo $info[1];
            }
            ?> </span></p>
<br/>

<form action="set-network.php" method="post">
        <pre style="font-size:150%">
设置学生机网络状态（设置后将会记住选项以表示当前状态）：
             <label><input name="network" type="radio"
                           value="0" <?php if (isset($_GET['setnetwork']) && $_GET['setnetwork'] == '0') {
                     echo "checked";
                 } ?>/>完全开放 </label>
             <label><input name="network" type="radio"
                           value="1" <?php if (isset($_GET['setnetwork']) && $_GET['setnetwork'] == '1') {
                     echo "checked";
                 } ?>/>仅关闭外网</label>
             <label><input name="network" type="radio"
                           value="2" <?php if (isset($_GET['setnetwork']) && $_GET['setnetwork'] == '2') {
                     echo "checked";
                 } ?>/>完全关闭（包括内网）</label>
                                 <input name="" type="submit" value="提交"/>
        </pre>
</form>

<hr/>
<p id="notice">
<pre style="font-size:120%">
<span style="color: red;font-weight: bold;font-size: 140%">注意：</span>
        1.该操作系统只能用于多媒体机房和文理楼机房的控制；
        2.从上课前10分钟到您的课结束，您都有权限控制机房网络;
        3.您的课结束后网络将自动恢复到完全开放状态；
        例：您03和04节在文理楼105有课，那么从03节上课前十分钟到04节课下课机房网络都将处于您
        设置的状态，04节下课后网络将自动恢复到完全开放状态。
    </pre>
</p></body>
</html>
