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
<div align="center"><img src="../head.jpg" width="550"/>
</div>
<br>
<div align="left" style="margin-left: 20%;height:120%;overflow: hidden">
    <p>请输入要查询的日期及教师工号:
    <form name="selectDate" method="post" action="admin-query.php">
        <select name='year' onchange="YYMM()">
            <option value="">年</option>
        </select>
        <select name="month" id="month" onchange="MMDD(this.value)">
            <option value="">月</option>
        </select>
        <select name="day" id="day">
            <option value="">日</option>
        </select>
        <input type="text" value="请输入教师工号" onfocus="javascript:if(this.value=='请输入教师工号')this.value='';" name="tchID">
        <button type="submit" class="btn btn-warning">提交</button>
        <button type="button" class="btn btn-success" onclick="window.location.href='admin.php'">点此返回主操作界面</button>
    </form>
    <script language="JavaScript">
        window.onload = function () {
            var strYear = document.selectDate.year.outerHTML;
            var y = new Date().getFullYear();
            var strY = strYear.substring(0, strYear.length - 9);
            for (var i = y; i > (y - 2); i--) {
                strY += "<option value='" + i + "'> " + i + "</option>\r\n";
            }
            document.selectDate.year.outerHTML = strY + "</select>";
        }
        function YYMM() {
            var strM = '<select name="month" onchange="MMDD(this.value)"><option value="">月</option>';
            for (var i = 1; i < 10; i++) {
                strM += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
            }
            for (i = 10; i < 13; i++) {
                strM += "<option value='" + i + "'> " + i + "</option>\r\n";
            }
            document.selectDate.month.outerHTML = strM + "</select>";
        }
        function MMDD(strM) {
            var strD = '<select name="day"><option value="">日</option>';
            var yearValue = document.selectDate.year.options[document.selectDate.year.selectedIndex].value;
            switch (strM) {
                case'01':
                case'03':
                case'05':
                case'07':
                case'08':
                case'10':
                case'12':
                    for (var i = 1; i <= 9; i++) {
                        strD += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
                    }
                    for (i = 10; i <= 31; i++) {
                        strD += "<option value='" + i + "'> " + i + "</option>\r\n";
                    }
                    break;
                case '02':
                    if (isLeapYear(yearValue)) {
                        for (var i = 1; i <= 9; i++) {
                            strD += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
                        }
                        for (i = 10; i <= 29; i++) {
                            strD += "<option value='" + i + "'> " + i + "</option>\r\n";
                        }
                    } else {
                        for (var i = 1; i <= 9; i++) {
                            strD += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
                        }
                        for (i = 10; i <= 28; i++) {
                            strD += "<option value='" + i + "'> " + i + "</option>\r\n";
                        }
                    }
                    break;
                case '04':
                case '06':
                case '09':
                case '11':
                    for (var i = 1; i <= 9; i++) {
                        strD += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
                    }
                    for (i = 10; i <= 30; i++) {
                        strD += "<option value='" + i + "'> " + i + "</option>\r\n";
                    }
                    break;
            }
            document.selectDate.day.outerHTML = strD + "</select>";
        }
        function isLeapYear(year)//判断是否闰平年
        {
            return (0 == year % 4 && (year % 100 != 0 || year % 400 == 0))
        }
    </script>
    </p>
    <?php
    /**
     * 该程序实现教师查询自己操作记录和管理员查询所有教师操作记录的功能
     */
    $host = "localhost";
    $username = "root";
    $password = "";

    $db = "operating_log";
    $con = mysqli_connect($host, $username, $password, $db);
    if (mysqli_connect_errno()) {
        echo "连接失败";
        exit();
    }
    @$time = $_POST['year'];
    @$tchID = $_POST['tchID'];
    @$display = $_POST['year'] . "年";
    if (@$_POST['month']) {
        @$time = $time . "-" . $_POST['month'];
        @$display = $display . $_POST['month'] . "月";
        if (@$_POST['day']) {
            @$time = $time . "-" . $_POST['day'];
            @$display = $display . $_POST['day'] . "日";
        }
    }
    if ($tchID != null && $tchID != '请输入教师工号') {
        echo "<p>当前查询工号为 <span style='background-color: lightgreen ;'>" . $tchID . "</span> 的老师在 <span style='background-color: lightgreen ;'>" . $display . "</span>的操作记录</p>";
    }
    $sql = "select * from log where tchID='$tchID' and time LIKE '%$time%'";
    $result = mysqli_query($con, $sql);
    ?>
    <textarea rows="25" cols="70" readonly="readonly">
    <?php
    echo "++++操作时间++++工号+++++操作教室+++具体操作++++\n";
    if ($time) {
        echo "结果如下:\n";
        if ($tchID != null && $tchID != '请输入教师工号') {
            if (mysqli_num_rows($result) < 1) {
                echo "\n当前日期无操作记录";
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    echo $row[0] . "&nbsp;&nbsp;&nbsp;" . $row[1] . "&nbsp;&nbsp;&nbsp;" . $row[2] . "&nbsp;&nbsp;&nbsp;" . $row[3] . "\n";
                }
            }
        } else {
            echo "\n请输入教师工号(>_<)";
        }
    }
    ?>
</textarea>
</div>
<br><br><br>
<p>sadfo</p>
<div style="background-color: grey;width: 100%;text-align:left">
<pre
        style="position: fixed;margin: 0 auto;bottom: 0;width: 100%; font-family: 幼圆; color: white;font-size: medium;background-color: grey;">
<span style="color: red;font-weight: bold;font-size: 140%;">注意：</span>
若只输入年，则将查询全年记录；
若输入年月则将查询该年该月的全部记录；
</pre>
</div>

</body>
</html>