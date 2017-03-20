<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set Date</title>
</head>
<script language="JavaScript">
    window.onload = function () {
        strYYYY = document.form1.YYYY.outerHTML;
        strMM = document.form1.MM.outerHTML;
        strDD = document.form1.DD.outerHTML;
        MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        //先给年下拉框赋内容
        var y = new Date().getFullYear();
        var str = strYYYY.substring(0, strYYYY.length - 9);
        str += "<option value='" + y + "'> " + y + "</option>\r\n";
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
    }
    function YYYYMM(str) //年发生变化时日期发生变化(主要是判断闰平年)
    {
        var MMvalue = document.form1.MM.options[document.form1.MM.selectedIndex].value;
        if (MMvalue == "") {
            document.form1.DD.outerHTML = strDD;
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
            document.form1.DD.outerHTML = strDD;
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
            s += "<option value='" + 0 + i + "'> " + 0 + i + "</option>\r\n";
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
<body>
<div align="center"><img src="../head.jpg" width="550"/>
</div>
<?php
require('../possess/mysql.php');
$sql_currentFirstDay = "select * from thefirstday";
$result = mysqli_query($con, $sql_currentFirstDay);
$currentFirstDay = mysqli_fetch_array($result);
echo "<p>当前已设置的开学第一天为 : <span style='text-decoration-line: underline;font-weight: bold'>" . $currentFirstDay[0] . "年" . "$currentFirstDay[1]" . "月" . $currentFirstDay[2] . "日</span></p>";
?>
<p>请确认后输入要设置为本学期第一天的日期:
<form name="form1" method="post" action="admin-setDate.php" onsubmit="return confirm('确认修改日期？')">
    <select name='YYYY' id="year"></select>
    <select name="MM" id="month" onchange="MMDD(this.value)">
        <option value="">月</option>
    </select>
    <select name="DD" id="day">
        <option value="">日</option>
    </select>
    <input type="submit" id="submit" value="提交">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="admin.php">点此返回主操作界面</a>
</form>
<?php
@$year = $_POST["YYYY"];
@$month = $_POST["MM"];
@$day = $_POST["DD"];
if ($month != 0 && $day != 0 && $year != 0) {
    $cleanTable = "truncate table thefirstday";
    mysqli_query($con, $cleanTable);
    $sql_thefirstday = "INSERT INTO `thefirstday` (`year`, `month`, `day`) VALUES ('$year','$month', '$day'); ";
    if (mysqli_query($con, $sql_thefirstday)) {
        echo "<script>alert('设置成功 !  本学期第一天将从 " . $year . "年" . $month . "月" . $day . "日" . " 开始计算');
                      window.location.href='admin-setDate.php';</script>";
    }
} else if ($month != 0 || $day != 0) {
    echo "<script>alert('设置失败,请输入正确的日期');</script>";
}
?>
</p>
</body>
</html>