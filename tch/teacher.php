<?php
/** to be continue
 * TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 * 1.实际课表中的字段位置可能与现在不一致,拿到详细数据库后要进行调整;
 * 2.上课时间例如1234连在一起,注意处理
 * T:::::::::::::::::::::T         B::::::BBBBBB:::::B            CC:::::::::::::::C
 * TTTTTT  T:::::T  TTTTTT           B::::B     B:::::B          C:::::C       CCCCCC
 * T:::::T                   B::::B     B:::::B         C:::::C
 * T:::::T                   B::::BBBBBB:::::B         C:::::C
 * T:::::T                   B:::::::::::::BB          C:::::C
 * T:::::T                   B::::BBBBBB:::::B         C:::::C
 * T:::::T                   B::::B     B:::::B        C:::::C
 * T:::::T                   B::::B     B:::::B        C:::::C
 * T:::::T                   B::::B     B:::::B         C:::::C       CCCCCC
 * TT:::::::TT               BB:::::BBBBBB::::::B          C:::::CCCCCCCC::::C
 * T:::::::::T               B:::::::::::::::::B            CC:::::::::::::::C
 * T:::::::::T               B::::::::::::::::B               CCC::::::::::::C
 * TTTTTTTTTTT               BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 */
/**
 * 教师操作界面
 */

?>
<?php
require('../possess/mysql.php');
require('../possess/function.php');
@session_start();
if ($_SESSION['ID'] == false) {
    header("location:../possess/login.php");
}
if (@$_SESSION["status"] != "admin") {
    @$_SESSION["status"] = "tch"; //将用户身份赋值为教师
}
$tchID = $_SESSION['ID'];
$today = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from thefirstday'));
$firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];
$days = calDays($firstDay, $today);      /*当天和本学期第一天中间隔了多少天*/
$whichweek = whichWeek($days);          /*当前是第几周*/
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
    <script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<script type="text/javascript">
    function checkConfirm() {
        if (window.confirm("确认修改网络状态?")) {
            alert("修改成功");
            return true;
        }
        return false;
    }
</script>
<body>
<div align="center">
    <img src="/imgs/title.png" width="550"/>
</div>
<div align='left' class="main">
    <div>
        <?php
        echo "<p><span style='font-weight: bold;font-size: 110%'>" . $tchID . "</span>  老师, 您好。";
        $o = 0;/*当天的课程数*/
        ?>
        <div class="btn-group">
            <button style="height: 45px;" class="btn btn-danger" onclick='window.location.href="../possess/logout.php"'>
                点此注销
            </button>
            <button style="height: 45px;" class="btn btn-info" onclick='window.location.href="query.php"'>
                点此查看您的操作记录
            </button>
            <button style="height: 45px;" class="btn btn-primary"
                    onclick='window.location.href="../possess/reset-password.php"'>点此修改密码
            </button>
            <?php
            if ($_SESSION["status"] == "admin") {
                echo '<button style="height: 45px;" class="btn btn-success" onclick=window.location.href="../admin/admin.php">点此返回管理员界面</button >';
            }
            ?>
        </div>
        <p>今天是 第 <span
                    class="todayIs"> <?php echo $whichweek ?></span><?php echo " 周 <span class='todayIs'>周" . $day[date("w")] . '</span> ;' ?>

            <?php
            $sql_schedule = "select * from schedule 
                        WHERE tchID='$tchID' 
                        AND find_in_set('$whichweek',detailsOfWeeks) 
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
                    if (strstr($info['locationOfClass'], "微") || strstr($info['locationOfClass'], "文理")) {
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
                $info['locationOfClass'] = "无课";
                echo $info['locationOfClass'];
            }
            echo "</div>";
            ?>
            <?php
            /*直接把时间段分割成上课节数*/
            $nowTime = date('H:i');/*赋值当前时间*/
            switch ($nowTime) {
                /*由当前时间$nowTime得到当前时间所在的周几的第几节课$nowPermit*/
                case (strtotime($nowTime) <= strtotime('09:50')):
                    $nowPermit = '0102';
                    break;
                case (strtotime('09:50') < strtotime($nowTime) && strtotime($nowTime) <= strtotime('12:00')):/*至此为上午*/
                    $nowPermit = '0304';
                    break;
                case (strtotime('12:00') < strtotime($nowTime) && strtotime($nowTime) <= strtotime('15:50')):
                    $nowPermit = '0506';
                    break;
                case (strtotime('15:50') < strtotime($nowTime) && strtotime($nowTime) <= strtotime('18:00')):/*至此为下午*/
                    $nowPermit = '0708';
                    break;
                case (strtotime('18:00') < strtotime($nowTime) && strtotime($nowTime) <= strtotime('20:50')): /*至此为10节课*/
                    $nowPermit = '0910';
                    break;
                case (strtotime('20:50') < strtotime($nowTime) && strtotime($nowTime) <= strtotime('24:00')):/*至此为全天结束*/
                    $nowPermit = '11';
                    break;
                default:
                    $nowPermit = '0000';
                    break;
            }
            /** to be continue
             * TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
             * 第11节课的网络状态设置应该和第9&10之间不断,还有1234连课,检测下$nowPermit
             * T:::::::::::::::::::::T         B::::::BBBBBB:::::B            CC:::::::::::::::C
             * TTTTTT  T:::::T  TTTTTT           B::::B     B:::::B          C:::::C       CCCCCC
             * T:::::T                   B::::B     B:::::B         C:::::C
             * T:::::T                   B::::BBBBBB:::::B         C:::::C
             * T:::::T                   B:::::::::::::BB          C:::::C
             * T:::::T                   B::::BBBBBB:::::B         C:::::C
             * T:::::T                   B::::B     B:::::B        C:::::C
             * T:::::T                   B::::B     B:::::B        C:::::C
             * T:::::T                   B::::B     B:::::B         C:::::C       CCCCCC
             * TT:::::::TT               BB:::::BBBBB B::::::B          C:::::CCCCCCCC::::C
             * T:::::::::T               B:::::::::::::::::B            CC:::::::::::::::C
             * T:::::::::T               B::::::::::::::::B               CCC::::::::::::C
             * TTTTTTTTTTT               BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
             */
            //$nowPermit = '040102';/*临时设置*/
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
                        $nowPermitClassroomName = $operation[$i][1];
//                    echo "$nowPermitClassroomName\n";
                        break;
                    }
                }
                echo "<span style='text-decoration-line: underline;color: red'>" . $nowPermitClassroomName . "&nbsp;</span>";/*输出当前可操作的机房地点*/
                echo "<br/><p id='now_net_status' style='font-size:120%;font-weight: bold;' >当前可操作教室网络状态:</p>";
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///取交换机对应接口下的网络状态////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
                echo '<div style="font-size: 120%">
设置学生机网络状态:<br>
<form action="set-network.php" onsubmit="return checkConfirm()" method="post" style="margin-left: 10%">
        <label><input name="network" type="radio" value="0" >完全开放 </label><br>
        <label><input name="network" type="radio" value="1" >仅关闭外网</label><br>
        <label><input name="network" type="radio" value="2" >完全关闭(包括内网)</label><br>    
        <label><input type="hidden" name="classroomName" value=' ?>
                <?php
                echo $nowPermitClassroomName;
                echo '></label><br>
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
1.该控制系统只能用于多媒体机房和文理楼机房的控制；
2.如果您需要临时换教室,请直接联系管理员修改；
3.从上课前10分钟到您的课结束，您都有权限控制机房网络；
4.您的课结束后网络将自动恢复到完全开放状态；
  例：您03和04节在文理楼105有课，那么从03节上课前十分钟到04节课下课机房网络都将处于您设置的状态，04节下课后网络将自动恢复到完全开放状态。
</pre>
</div>
</body>
</html>