<html>
<head>
    <title>操作记录查询</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body>
<p>请输入要查询的日期:
<form name="form1" method="post" action="query.php">
    <select name='YYYY' onchange="YYYYMM(this.value)">
        <option value="">年</option>
    </select>
    <select name="MM" onchange="MMDD(this.value)">
        <option value="">月</option>
    </select>
    <select name="DD">
        <option value="">日</option>
    </select>
    <input type="submit" value="提交">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="teacher.php">点此返回主操作界面</a>
</form>
<script language="JavaScript">
    window.onload = function () {
        strYYYY = document.form1.YYYY.outerHTML;
        strMM = document.form1.MM.outerHTML;
        strDD = document.form1.DD.outerHTML;
        MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        //先给年下拉框赋内容
        var y = new Date().getFullYear();
        var str = strYYYY.substring(0, strYYYY.length - 9);
        for (var i = (y - 1); i < (y + 1); i++) //以今年为准，前30年，后30年
        {
            str += "<option value='" + i + "'> " + i + "</option>\r\n";
        }
        document.form1.YYYY.outerHTML = str + "</select>";
        //赋月份的下拉框
        var str = strMM.substring(0, strMM.length - 9);

        for (var i = 1; i < 10; i++) {
            str += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
        }
        for (var i = 10; i < 13; i++) {
            str += "<option value='" + i + "'> " + i + "</option>\r\n";
        }
        document.form1.MM.outerHTML = str + "</select>";
        document.form1.YYYY.value = y;
        var n = MonHead[new Date().getMonth()];
        if (new Date().getMonth() == 1 && IsPinYear(YYYYvalue)) n++;
        writeDay(n); //赋日期下拉框
        document.form1.DD.value = new Date().getDate();
    }
    function YYYYMM(str) //年发生变化时日期发生变化(主要是判断闰平年)
    {
        var MMvalue = document.form1.MM.options[document.form1.MM.selectedIndex].value;
        if (MMvalue == "") {
            DD.outerHTML = strDD;
            return;
        }
        var n = MonHead[MMvalue - 1];
        if (MMvalue == 2 && IsPinYear(str)) n++;
        writeDay(n)
    }
    function MMDD(str) //月发生变化时日期联动
    {
        var YYYYvalue = document.form1.YYYY.options[document.form1.YYYY.selectedIndex].value;
        if (str == "") {
            DD.outerHTML = strDD;
            return;
        }
        var n = MonHead[str - 1];
        if (str == 2 && IsPinYear(YYYYvalue)) n++;
        writeDay(n)
    }
    function writeDay(n) //据条件写日期的下拉框
    {
        var s = strDD.substring(0, strDD.length - 9);
        for (var i = 1; i < 10; i++) {
            s += "<option value='" + i + "'> " + 0 + i + "</option>\r\n";
            document.form1.DD.outerHTML = s + "</select>";
        }
        for (var i = 10; i < (n + 1); i++)
            s += "<option value='" + i + "'> " + i + "</option>\r\n";
        document.form1.DD.outerHTML = s + "</select>";
    }
    function IsPinYear(year)//判断是否闰平年
    {
        return (0 == year % 4 && (year % 100 != 0 || year % 400 == 0))
    }
</script>
</body>
</html>
<?php
/**
 * 该程序实现教师查询自己操作记录和管理员查询所有教师操作记录的功能
 */
//session_start();
//header("content-type:text/html;charset=utf-8");
$host = "localhost";
$username = "root";
$password = "";
$db = "operating_log";
$con = mysqli_connect($host, $username, $password, $db);
if (mysqli_connect_errno()) {
    echo "连接失败";
    exit();
}
@$time = $_POST['YYYY'];
@$diasplay = $_POST['YYYY'] . "年";
if (@$_POST['MM']) {
    @$time = $time . "-" . $_POST['MM'];
    @$diasplay = $diasplay . $_POST['MM'] . "月";
    if (@$_POST['DD']) {
        @$time = $time . "-" . $_POST['DD'];
        @$diasplay = $diasplay . $_POST['DD'] . "日";
    }
}
echo "当前查询日期:&nbsp;&nbsp;&nbsp;" . $diasplay . "<br/>";
$teacherName = $_SESSION['username'];
$sql = "select * from log where name='$teacherName' and time LIKE '%$time%'";
$result = mysqli_query($con, $sql);
?>
<textarea rows="30" cols="70">
    <?php
    echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
    if ($time) {
        echo "结果如下:\n";
        if (mysqli_num_rows($result) < 1) {
            echo "\n当前日期无操作记录";
        } else {
            while ($row = mysqli_fetch_array($result)) {
                echo $row[0] . "&nbsp;&nbsp;&nbsp;" . $row[1] . "&nbsp;&nbsp;&nbsp;" . $row[2] . "&nbsp;&nbsp;&nbsp;" . $row[3] . "\n";
            }
        }
    }
    ?>
</textarea>