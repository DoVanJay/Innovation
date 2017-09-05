<html>
<head>
    <title>当前用户操作记录查询</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<div align="left" class="main">
    <p>请输入要查询的日期:
    <form name="selectDate" method="post" action="query.php">
        <select name='year' onchange="YYMM()">
            <option value="">年</option>
        </select>
        <select name="month" onchange="MMDD(this.value)">
            <option value="">月</option>
        </select>
        <select name="day">
            <option value="">日</option>
        </select>
        <div class="btn-group">
            <button type="submit" class="btn btn-info">提交</button>
            <button type="button" class="btn btn-success" onclick="window.location.href='teacher.php'">点此返回主操作界面
            </button>
        </div>
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
        };

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
            return (0 === year % 4 && (year % 100 !== 0 || year % 400 === 0))
        }
    </script>

    <?php
    /**
     * 该程序实现教师查询自己操作记录和管理员查询所有教师操作记录的功能
     */
    require("../possess/mysql.php");
    @session_start();
    @$time = $_POST['year'];
    @$display = $_POST['year'] . "年";
    if (@$_POST['month']) {
        @$time = $time . "-" . $_POST['month'];
        @$display = $display . $_POST['month'] . "月";
        if (@$_POST['day']) {
            @$time = $time . "-" . $_POST['day'];
            @$display = $display . $_POST['day'] . "日";
        }
    }
    if ($display != "年") {
        echo "<p>当前查询日期: <span style='background-color: lightgreen ;'>" . $display . "</span></p>";
    }
    $tchID = $_SESSION['ID'];
    $sql = "select * from operating_log where tchID='$tchID' and time LIKE '%$time%'";
    $result = mysqli_query($con, $sql);
    ?>

    <?php
    if ($time) {
        echo '<textarea rows="26" cols="100" readonly="readonly" style="opacity: 0.4;color: black;font-size: 120%">结果如下:';
        if (mysqli_num_rows(mysqli_query($con, $sql)) < 1) {
            echo "\n当前日期无操作记录";
        } else {
            echo "\n           操作时间 ++++++ 工号 ++++++ 操作教室 ++++++ 具体操作\n\n";
            while ($row = mysqli_fetch_array($result)) {
                echo $row[0] . "&nbsp;&nbsp;&nbsp;" . $row[1] . "&nbsp;&nbsp;&nbsp;" . $row[2] . "&nbsp;&nbsp;&nbsp;" . $row[3] . "\n";
            }
        }
        echo "</textarea>";
    } else {
        if ($time != null) {
            echo "\n请输入正确的日期(>_<)";
        }
    }
    ?>

</div>
<div class="bottom-remind">
<pre>
<span>注意：</span>
若只输入年，则将查询全年记录；
若输入年月则将查询该年该月的全部记录；
</pre>
</div>
</body>
</html>