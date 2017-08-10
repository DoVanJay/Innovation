<html>
<head>
    <title>全员操作记录查询</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div align="center"><img src="/imgs/title.png" width="550"/>
</div>
<div align="left" class="main">
    <p>请输入要查询的教师工号:
    <form name="selectDate" method="post" action="admin-query-class.php">
        <input style="width: 300px;display: inline;" class="form-control" placeholder="请输入教师工号" name="tchID">
        <div class="btn-group">
            <button type="submit" class="btn btn-warning">提交</button>
            <button type="button" class="btn btn-success" onclick="window.location.href='admin.php'">点此返回主操作界面</button>
        </div>


        <?php
        require('../possess/mysql.php');//引入数据库连接
        require('../possess/function.php');//引入函数
        @session_start();
        @$tchID = $_POST['tchID'];
        $today = date('y-m-d');
        $day = array('日', '一', '二', '三', '四', '五', '六');
        $firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from thefirstday'));
        $firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];
        $days = calDays($firstDay, $today);      /*当天和本学期第一天中间隔了多少天*/
        $whichweek = whichWeek($days);          /*当前是第几周*/
        $o = 0;/*当天的课程数*/
        if (date("w") != 0) {
            $dayInWeek = date("w") . "%";         /*对当前是周几的判断*/
        } else {
            $dayInWeek = 7 . "%";
        }
        $sql_schedule = "select * from schedule 
                        WHERE tchID='$tchID' 
                        AND find_in_set('$whichweek',detailsOfWeeks) 
                        AND timeForClass like '$dayInWeek' ";
        $result = mysqli_query($con, $sql_schedule);
        $operation = null;
        if (mysqli_num_rows($result)) {
            $n = mysqli_num_rows($result);
            echo '
                <p class="table-top-p">' . $tchID . '老师今天在机房的课程如下：</p>
                <hr class="table-top-hr">
                <table class="table table-hover" style="margin-top:0;width:70%;color: gray;background-color: rgba(255, 255, 255, 0.4)">
                    <thead>
                    <tr>
                        <th>上课时间</th>
                        <th>上课地点</th>
                        <th>操作</th>
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
                        <td><a href="">删除</a></td>
                    </tr>';
                $n = $n - 1;
            }
            echo '
                    </tbody>
                    </table>
            ';
        } else if ($tchID) {
            $info['locationOfClass'] = "无课";
            echo $info['locationOfClass'];
        }
        echo "</div>";
        ?>
</div>
</body>
</html>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 8/10/2017
 * Time: 10:09 PM
 */