<!--
注意:
1.实际课表中的字段位置可能与现在不一致,拿到详细数据库后要进行调整;
2.
-->
<?php
/**
 * 教师操作界面
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
require('./mysql.php');
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
<script type="text/javascript">
    function checkConfirm() {
        if (window.confirm("确认修改网络状态?")) {
            return true;
        }
        return false;
    }
</script>
<body>
<div align="center"><img src="head.jpg"  width="550"/>
</div>
<br/><br/>
<?php
echo $username . " 老师,您好。";
?>
<?php
echo '点此 <a href="off.php">注销</a>&nbsp;&nbsp;;&nbsp; 点此<a href="query.php">查看您的操作记录</a>';
$o = 0;/*当天的课程数*/
?>
<p>今天是 第<span
        style="text-decoration-line: underline"> <?php echo "&nbsp" . $whichweek . " " ?></span><?php echo "周 周" . $day[date("w")] . '&nbsp;&nbsp;;' ?>
    &nbsp;&nbsp;&nbsp;您今天 <span style="text-decoration-line: underline">
            <?php
            if (mysqli_num_rows($result)) {
                $n = mysqli_num_rows($result);
                while ($n > 0) {
                    $info = mysqli_fetch_array($result);            /*取出当前老师课表信息*/
                    $SKSJ = (string)$info[0];                       /*取出上课时间*/
                    $sksjPY = exec("python dayInWeek.py $SKSJ");    /*对上课时间进行切片*/
                    $m = strlen($sksjPY);
                    if (strstr($info[1], "微") || strstr($info[1], "文理")) {
                        $operation[$o][0] = $info[0];/*可操作的机房课程时间*/
                        $operation[$o][1] = $info[1];/*可操作的机房课程地点*/
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
<?php
/*直接把时间段分割成上课节数*/
$nowTime = date('H:i');/*赋值当前时间*/
switch ($nowTime) {
    case (strtotime($nowTime) < strtotime('09:50')):
        $nowPermit = date('w') . '0102';
    case (strtotime('10:10') < strtotime($nowTime) && strtotime($nowTime) < strtotime('12:00')):/*至此为上午*/
        $nowPermit = date('w') . '0304';
    case (strtotime('13:00') < strtotime($nowTime) && strtotime($nowTime) < strtotime('15:50')):
        $nowPermit = date('w') . '0506';
    case (strtotime('16:10') < strtotime($nowTime) && strtotime($nowTime) < strtotime('18:00')):/*至此为下午*/
        $nowPermit = date('w') . '0708';
    case (strtotime('18:30') < strtotime($nowTime) && strtotime($nowTime) < strtotime('20:50')): /*至此为10节课*/
        $nowPermit = date('w') . '0910';/*待确定*/
    case (strtotime('21:00') < strtotime($nowTime) && strtotime($nowTime) < strtotime('22:00')):/*至此为全天结束*/
        $nowPermit = date('w') . '091011';/*待确定*/
    default:
        $nowPermit = '0000';
}
/** to be continue
TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
第11节课的网络状态设置应该和第9&10之间不断
T:::::::::::::::::::::T         B::::::BBBBBB:::::B            CC:::::::::::::::C
TTTTTT  T:::::T  TTTTTT           B::::B     B:::::B          C:::::C       CCCCCC
        T:::::T                   B::::B     B:::::B         C:::::C
        T:::::T                   B::::BBBBBB:::::B         C:::::C
        T:::::T                   B:::::::::::::BB          C:::::C
        T:::::T                   B::::BBBBBB:::::B         C:::::C
        T:::::T                   B::::B     B:::::B        C:::::C
        T:::::T                   B::::B     B:::::B        C:::::C
        T:::::T                   B::::B     B:::::B         C:::::C       CCCCCC
      TT:::::::TT               BB:::::BBBBBB::::::B          C:::::CCCCCCCC::::C
      T:::::::::T               B:::::::::::::::::B            CC:::::::::::::::C
      T:::::::::T               B::::::::::::::::B               CCC::::::::::::C
      TTTTTTTTTTT               BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 */
$nowPermit = '60102';/*临时设置*/


$todayLesson = ' + ';
for ($l = 0; $l < $o; $l++) {/*$o为当天在机房上的课程数*/
    $todayLesson = $todayLesson . ' + ' . $operation[$l][0];
}
if (strstr($todayLesson, $nowPermit)) {   /*根据时间判断当前是否有可操作的教室*/
    echo "您当前可操作:&nbsp;&nbsp;&nbsp;";
    $i = 0;
    for ($i; $i < $o; $i++) {
        if ($nowPermit == $operation[$i][0]) {
            $nowPermitClassroomname = $operation[$i][1];
        }
    }
    echo "<span style=\"text-decoration-line: underline\">" . $nowPermitClassroomname . "&nbsp;;</span>";/*输出当前可操作的机房地点*/
    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////
    ///取交换机对应接口下的网络状态////////////////////////
    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////
    echo "<br/><p id='now_net_status' style='font-size:130%;font-weight: bold' >当前网络状态:</p>";

    echo '<form action="set-network.php" onsubmit="return checkConfirm()" method="post">
        <pre style="font-size:150%">  
设置学生机网络状态(设置后将会记住选项以表示当前状态):
        <label><input name="network" type="radio" value="0" >完全开放 </label>
        <label><input name="network" type="radio" value="1" >仅关闭外网</label>
        <label><input name="network" type="radio" value="2" >完全关闭(包括内网)</label>
        <input name="classroomName" type="hidden" value="' . $nowPermitClassroomname . '">
                                <input name="" type="submit" value="提交"/>
        </pre>
        </form>';
} else {
    echo "<p style='font-size: 130%;color: orangered;font-weight: bold'>您当前时间段没有可操作的教室</p>";
}
?>
<hr/>
<p id="notice">
<pre style="font-size:120%">
<span style="color: red;font-weight: bold;font-size: 140%">注意：</span>
        1.该控制系统只能用于多媒体机房和文理楼机房的控制；
        2.从上课前10分钟到您的课结束，您都有权限控制机房网络；
        3.您的课结束后网络将自动恢复到完全开放状态；
        例：您03和04节在文理楼105有课，那么从03节上课前十分钟到04节课下课机房网络都将处于您
        设置的状态，04节下课后网络将自动恢复到完全开放状态。
    </pre>
</p></div>
</body>
</html>