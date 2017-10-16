<html>
<head>
    <title>全员机房课程操作</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<div align="left" class="main">
    <p>请输入要查询的教师工号:
    <form name="selectDate" method="post" action="admin-query-class.php">
        <input style="width: 300px;display: inline;" class="form-control" placeholder="请输入教师工号" name="query_tchID"
               id="query_tchID">
        <div class="btn-group">
            <button type="submit" class="btn btn-warning" id="sub" onclick="storage(this)">提交</button>
            <button type="button" class="btn btn-success" onclick="window.location.href='admin.php'">点此返回主操作界面</button>
        </div>
    </form>

    <?php
    require('../possess/mysql.php');//引入数据库连接
    require('../function/function.php');//引入函数
    @session_start();
    @$query_tchID = $_POST['query_tchID'];
    $today = date('y-m-d');
    $day = array('日', '一', '二', '三', '四', '五', '六');
    $firstDay = mysqli_fetch_array(mysqli_query($local_con, 'select * from the_first_day'));
    $firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];
    $days = calDays($firstDay, $today);      /*当天和本学期第一天中间隔了多少天*/
    $whichWeek = whichWeek($days);          /*当前是第几周*/
    $o = 0;/*当天的课程数*/
    if (date("w") != 0) {
        $dayInWeek = date("w") . "%";         /*对当前是周几的判断*/
    } else {
        $dayInWeek = 7 . "%";
    }
    $sql_schedule = "select * from course_timetable 
                        WHERE tchID='$query_tchID' 
                        AND find_in_set('$whichWeek',detailsOfWeeks) 
                        AND timeForClass like '$dayInWeek' ";
    $result = mysqli_query($local_con, $sql_schedule);
    $operation = null;
    if (@mysqli_num_rows($result)) {
        $n = mysqli_num_rows($result);
        echo '
                <p class="table-top-p" id="show-result"><span style="background-color: lightgreen ;">' . $query_tchID . '</span>  老师今天在机房的课程如下：</p>
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
                        <td style="display: none;">' . $info['id'] . '</td>
                        <td>' . $timeOfClass . '</td>
                        <td><span style="color: red">' . $info['locationOfClass'] . '</span></td>
                        <td><button class="btn btn-info" onclick="deleteClass(this)">删除</button></td>
                    </tr>
                    ';
            $n = $n - 1;
        }
        echo '
                    </tbody>
                    </table>';
    } else if ($query_tchID) {
        echo '
                <div class="alert alert-success" id="show-result" style="margin-top: 30px;width: 40%;height: 10%;">
                <a class="alert-link">' . $query_tchID . '  老师今天在机房无课</a>
                </div>
                ';
    }
    echo "</div>";
    ?>
</div>
<div class="bottom-remind">
<pre>
<span>注意：</span>
1.仅可查询指定教师当天在机房的课程；
2.如果教师有课可进行删除课程操作；
</pre>
</div>
<script>
    //页面加载时检测localStorage中有无query_tchID
    window.onload = function () {
        //有则将其填入输入框
        if (localStorage.getItem("query_tchID")) {
            document.getElementById('query_tchID').value = localStorage.getItem("query_tchID");
            //如果当前没有显示查询结果则点击提交按钮进行查询
            if (!(document.getElementById("show-result"))) {
                document.getElementById("sub").click();
            }
        }
    };

    //提交query_tchID时将其存入localStorage
    function storage(button) {
        var query_tchID = button.parentNode.parentNode.childNodes[1].value;
        localStorage.setItem("query_tchID", query_tchID);
    }

    //    获取指定行的课程号并创建表单提交以删除课程
    function deleteClass(button) {
        var con = confirm("确认删除？");
        if (con === true) {
            var id = button.parentNode.parentNode.childNodes[1].innerHTML;
            var form = document.createElement("form");
            form.action = "admin-query-class.php";
            form.method = "post";
            document.body.appendChild(form);
            var input = document.createElement("input");
            input.type = "text";
            input.name = "id";
            input.value = id;
            form.appendChild(input);
            form.submit();
            document.body.removeChild(form);
        }
    }
</script>

<?php
$id = @$_POST['id'];
$delete_sql = "delete from course_timetable where id=$id";
$result = mysqli_query($local_con, $delete_sql);
$affected = mysqli_affected_rows($local_con);
if ($affected > 0) {
    $log_sql = 'insert operation_log(time,tchID,classroomName,operation) values(NOW(),"' . $_SESSION["ID"] . '","#","删除' . $query_tchID . '老师机房课程")';
    mysqli_query($local_con, $log_sql);
    echo "<script>
            alert('删除成功');
          </script>";
}
?>
</body>
</html>