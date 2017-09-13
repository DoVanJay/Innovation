<?php
/**
 * 教师操作界面
 */
ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
set_time_limit(0);//设置不受不响应最长时间限制

require '../possess/mysql.php';
require '../function/function.php';
require '../config/config.php';
require '../possess/control_switch.php';
@session_start();
if ($_SESSION['ID'] == false) {
    header("location:../possess/login.php");
}
if (@$_SESSION["status"] !== "admin") {
    @$_SESSION["status"] = "tch"; //将用户身份赋值为教师
}

if (!@$_GET['operation_result']) {
    $_GET['operation_result'] = -1;
}
if (@$_GET['operation_result'] == 111) {
    echo "<script>alert('修改网络状态成功');window.location.href='tch.php';</script>";
} elseif (@$_GET['operation_result'] == 000) {
    echo "<script>alert('修改网络状态失败');window.location.href='tch.php';</script>";
}

$_SESSION["vlan"] = null;
$_SESSION["current_acl"] = null;
$_SESSION["switch_passwd"] = null;


$tchID = $_SESSION['ID'];
$today = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from the_first_day'));
$firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];
$days = calDays($firstDay, $today);      /*当天和本学期第一天中间隔了多少天*/
$whichWeek = whichWeek($days);          /*当前是第几周*/
if (date("w") != 0) {
    $dayInWeek = date("w") . "%";         /*对当前是周几的判断*/
} else {
    $dayInWeek = 7 . "%";
}
?>
<html>
<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>教师操作界面</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<script type="text/javascript">
    function checkConfirm() {
        return !!window.confirm("确认修改网络状态?");
    }
</script>
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

<div align='left' class="main">
    <div>
        <?php
        echo "<p><span style='font-weight: bold;font-size: 110%'>" . $tchID . "</span>  老师, 您好。";
        $o = 0;/*当天的课程数*/
        ?>
        <div class="btn-group">
            <button style="height: 45px;" class="btn btn-danger"
                    onclick='window.location.href="../possess/logout.php"'>
                点此注销
            </button>
            <button style="height: 45px;" class="btn btn-info" onclick='window.location.href="query-log.php"'>
                点此查看您的操作记录
            </button>
            <!--如果不使用密码登录方式，则删除下面的button标签-->
            <button style="height: 45px;" class="btn btn-primary"
                    onclick='window.location.href="../possess/reset-password.php"'>点此修改密码
            </button>
            <?php
            if ($_SESSION["status"] == "admin") {
                echo '<button style="height: 45px;" class="btn btn-success" onclick=window.location.href="../admin/admin.php">点此返回管理员界面</button >';
            }
            ?>
        </div>
        <p style="margin-top: 10px">今天是 第 <span
                    class="todayIs"> <?php echo $whichWeek ?></span><?php echo " 周 <span class='todayIs'>周" . $day[date("w")] . '</span> ;' ?>

            <?php
            $sql_schedule = "select * from course_timetable 
                        WHERE tchID='$tchID' 
                        AND find_in_set('$whichWeek',detailsOfWeeks) 
                        AND timeForClass like '$dayInWeek' ";
            $result = mysqli_query($con, $sql_schedule);
            $operation = null;
            if (mysqli_num_rows($result)) {
                $n = mysqli_num_rows($result);
                echo '
                <p class="table-top-p">您今天在机房的课程如下：</p>
                <hr class="table-top-hr">
                <table class="table table-hover" style="margin-top:0;width:70%;color: gray;background-color: rgba(255, 255, 255, 0.4)">
                    <thead>
                    <tr>
                        <th>上课时间</th>
                        <th>上课地点</th>
                    </tr>
                    </thead>
                    <tbody>
            ';
                while ($n > 0) {
                    $info = mysqli_fetch_array($result);            /*取出当前老师课表信息*/
                    $SKSJ = (string)$info['timeForClass'];          /*取出上课时间*/
                    $sksjPY = str_split($SKSJ);
                    $m = count($sksjPY);
                    foreach ($computer_room_title as $title)
                        if (strstr($info['locationOfClass'], $title)) {
                            $operation[$o][0] = $info['timeForClass'];/*可操作的机房课程时间*/
                            $operation[$o][1] = $info['locationOfClass'];/*可操作的机房课程地点*/
                            $o = $o + 1;
                        }
                    $timeOfClass = null;
                    for ($i = ($m - 1); $i >= 1; $i = $i - 2) {
                        if ($i == 2) {
                            $timeOfClass = "第" . $sksjPY[$i - 1] . $sksjPY[$i] . "节&" . $timeOfClass;
                        } elseif ($i == ($m - 1)) {
                            $timeOfClass = $sksjPY[$i - 1] . $sksjPY[$i] . "节";
                        } else {
                            $timeOfClass = $sksjPY[$i - 1] . $sksjPY[$i] . "节&" . $timeOfClass;
                        }
                    }
                    echo '
                    <tr>
                        <td>' . $timeOfClass . '</td>
                        <td><span style="color: red">' . $info['locationOfClass'] . '</span></td>
                    </tr>';
                    $n = $n - 1;
                }
                echo '
                    </tbody>
                    </table>
            ';
            } else {
                $info['locationOfClass'] = "您今天在机房无课";
                echo "<p style='font-weight: bold;font-size: 110%'>" . $info['locationOfClass'] . "</p>";
            }
            echo "</div>";
            ?>
            <?php
            /*直接把时间段分割成上课节数*/
            $nowTime = date('H:i');/*赋值当前时间*/
            switch ($nowTime) {
                /*由当前时间$nowTime得到当前时间所在的是第几节课$nowPermit*/
                case (strToTime($nowTime) <= strToTime('09:50')):
                    $nowPermit = '0102';
                    break;
                case (strToTime('09:50') < strToTime($nowTime) && strToTime($nowTime) <= strToTime('12:00')):/*至此为上午*/
                    $nowPermit = '0304';
                    break;
                case (strToTime('12:00') < strToTime($nowTime) && strToTime($nowTime) <= strToTime('15:50')):
                    $nowPermit = '0506';
                    break;
                case (strToTime('15:50') < strToTime($nowTime) && strToTime($nowTime) <= strToTime('18:00')):/*至此为下午*/
                    $nowPermit = '0708';
                    break;
                case (strToTime('18:00') < strToTime($nowTime) && strToTime($nowTime) <= strToTime('20:50')): /*至此为10节课*/
                    $nowPermit = '0910';
                    break;
                case (strToTime('20:50') < strToTime($nowTime) && strToTime($nowTime) <= strToTime('24:00')):/*至此为全天结束*/
                    $nowPermit = '11';
                    break;
                default:
                    $nowPermit = '0000';
                    break;
            }
            $todayLesson = null;
            if ($operation != null) {
                $todayLesson = $operation[0][0];
                for ($l = 1; $l < $o; $l++) {/*$o为当天在机房上的课程数*/
                    $todayLesson = $todayLesson . ' + ' . $operation[$l][0];
                }
            }
            if (strstr($todayLesson, $nowPermit)) {   /*根据时间判断当前是否有可操作的教室*/
                $nowPermitClassroomName = null;
                echo "<div class='classroom-now-control'>您当前可操作教室：";
                for ($i = 0; $i < $o; $i++) { /*$o为今天机房课的个数*/
                    if (strstr($operation[$i][0], $nowPermit)) {
                        $nowPermitClassroomName = $operation[$i]["1"];
                        //下课时间是第几节课，用来传给set-network来确定什么时候下课
                        $endTimestamp = substr((string)$operation[$i][0], -2);
                        break;
                    }

                }
                switch ($endTimestamp) {
                    case "01":
                        $endTimestamp = strtotime(date("y-m-d") . " 08:50:00");
                        break;
                    case "02":
                        $endTimestamp = strtotime(date("y-m-d") . " 09:50:00");
                        break;
                    case "03":
                        $endTimestamp = strtotime(date("y-m-d") . " 11:00:00");
                        break;
                    case "04":
                        $endTimestamp = strtotime(date("y-m-d") . " 12:00:00");
                        break;
                    case "05":
                        $endTimestamp = strtotime(date("y-m-d") . " 14:50:00");
                        break;
                    case "06":
                        $endTimestamp = strtotime(date("y-m-d") . " 15:50:00");
                        break;
                    case "07":
                        $endTimestamp = strtotime(date("y-m-d") . " 17:00:00");
                        break;
                    case "08":
                        $endTimestamp = strtotime(date("y-m-d") . " 18:00:00");
                        break;
                    case "09":
                        $endTimestamp = strtotime(date("y-m-d") . " 19:50:00");
                        break;
                    case "10":
                        $endTimestamp = strtotime(date("y-m-d") . " 20:50:00");
                        break;
                    default:
                        $endTimestamp = strtotime(date("y-m-d") . " 21:50:00");
                }
                echo "<span style='text-decoration-line: underline;color: red'>" . $nowPermitClassroomName . "&nbsp;</span>";/*输出当前可操作的机房地点*/
                echo "<br/><p id='now_net_status' style='font-size:120%;font-weight: bold;' >可操作教室的当前网络状态: <a style='color: black;'>";
///////////////////////////////////////////////////
///取交换机对应接口下的网络状态////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////

                $query_classroomInfo = "select * from classroom_info where classroom_name='" . $nowPermitClassroomName . "';";
                $classroomInfo = mysqli_fetch_array(mysqli_query($con, $query_classroomInfo));

                $nowPermitClassroomVlan = $classroomInfo["vlan"];
                $_SESSION["vlan"] = $nowPermitClassroomVlan;
                $nowPermitClassroomSwitchIp = $classroomInfo["switch_ip"];
                $_SESSION['SwitchIp'] = $nowPermitClassroomSwitchIp;
                $query_switch_passwd = "select passwd from switch_info where switch_ip='" . $nowPermitClassroomSwitchIp . "';";
                $switch_passwd = mysqli_fetch_array(mysqli_query($con, $query_switch_passwd))['passwd'];
                $_SESSION['switch_passwd'] = $switch_passwd;
                $current_acl = mysqli_fetch_array(mysqli_query($con, "select current_acl_num from classroom_info where classroom_name='" . $nowPermitClassroomName . "';"))['current_acl_num'];
                if ($current_acl) {
                    if (strstr($current_acl, $open_net_acl)) {
                        $_SESSION['current_acl'] = $open_net_acl;
                        echo "完全开放";
                    } elseif (strstr($current_acl, $only_campus_acl)) {
                        $_SESSION['current_acl'] = $only_campus_acl;
                        echo "仅可访问校园网";
                    } elseif (strstr($current_acl, $shutdown_net_acl)) {
                        $_SESSION['current_acl'] = $shutdown_net_acl;
                        echo "完全关闭对外网络";
                    }
                } else {
                    echo "<a style='color: tomato'>查询失败，请稍后再试</a>";
                }
                echo '</a></p><div style="font-size: 120%">
设置学生机网络状态:<br>
<form action="set-network.php" onsubmit="return checkConfirm()" method="post" style="margin-left: 10%">
        <label><input name="network" type="radio" value="0" >完全开放 </label><br>
        <label><input name="network" type="radio" value="1" >仅关闭外网</label><br>
        <label><input name="network" type="radio" value="2" >完全关闭(包括内网)</label><br>    
        <label><input type="hidden" name="classroomName" value=' . $nowPermitClassroomName . '></label><br>
        <label><input type="hidden" name="endTimestamp" value=' . $endTimestamp . '></label><br>
        <button class="btn btn-warning" style="margin-left: 50%;width: 120px;" type="submit" value="提交"/>提交</button>
</form>
</div>';
            } else {
                echo "<p class='no-classroom-to-control' '>您当前时间段没有可操作的教室</p>";
            }
            echo "</div>";
            ?>
    </div>
</div>
<div class="bottom-remind">
<pre>
<span>注意：</span>
1.每周从周一开始计算；若当前周次为负数(如-2)，则为开学之前的第2周（倒数）；没有第0周；
2.该控制系统只能用于多媒体机房和文理楼机房的控制；
3.如果您需要临时换教室，请直接联系管理员修改；
4.从上课前10分钟到您的课结束，您都有权限控制机房网络；
5.您的课结束后网络将自动恢复到完全开放状态；
  例：您03和04节在文理楼105有课，那么从03节上课前十分钟到04节课下课机房网络都将处于您设置的状态，04节下课后网络将自动恢复到完全开放状态。
</pre>
</div>
</body>
</html>
