<html>
<head>
    <title>全员操作记录查询</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body>
<div align="center"><img src="../head.jpg" width="550"/>
</div>
<p>请输入要查询的日期及教师工号:
<form name="form1" id="year" method="post" action="admin-query.php">
    <select name='YYYY' onchange="YYYYMM(this.value)">
        <option value="">年</option>
    </select>
    <select name="MM" id="month" onchange="MMDD(this.value)">
        <option value="">月</option>
    </select>
    <select name="DD" id="day">
        <option value="">日</option>
    </select>
    <input type="text" value="请输入教师工号" onfocus="javascript:if(this.value=='请输入教师工号')this.value='';" name="tchNum">
    <input type="submit" value="提交">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="admin.php">点此返回主操作界面</a>
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
    function jilu() {
        var month = document.getElementById('month').options[document.getElementById('month').selectedIndex].value;
        var day = document.getElementById('day').options[document.getElementById('day').selectedIndex].value;
        alert(day);
        document.form1.MM.value = month;
        document.form1.DD.value = day;
    }
</script>
</p>
<?php
/**
 * 该程序实现教师查询自己操作记录和管理员查询所有教师操作记录的功能
 */
/** to be continue
 * TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 * 教师重名怎么办?
 * 考虑到browser会崩溃  是否需要开放全年查询功能?或设定最多显示的数据大小
 * T:::::::::::::::::::::T         B::::::BBBBBB:::::B            CC:::::::::::::::C
 * TTTTTT  T:::::T  TTTTTT           B::::B     B:::::B          C:::::C       CCCCCC
 *         T:::::T                   B::::B     B:::::B         C:::::C
 *         T:::::T                   B::::BBBBBB:::::B         C:::::C
 *         T:::::T                   B:::::::::::::BB          C:::::C
 *         T:::::T                   B::::BBBBBB:::::B         C:::::C
 *         T:::::T                   B::::B     B:::::B        C:::::C
 *         T:::::T                   B::::B     B:::::B        C:::::C
 *         T:::::T                   B::::B     B:::::B         C:::::C       CCCCCC
 *       TT:::::::TT               BB:::::BBBBBB::::::B          C:::::CCCCCCCC::::C
 *       T:::::::::T               B:::::::::::::::::B            CC:::::::::::::::C
 *       T:::::::::T               B::::::::::::::::B               CCC::::::::::::C
 *       TTTTTTTTTTT               BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
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
@$time = $_POST['YYYY'];
@$tchNum = $_POST['tchNum'];
@$diasplay = $_POST['YYYY'] . "年";
if (@$_POST['MM']) {
    @$time = $time . "-" . $_POST['MM'];
    @$diasplay = $diasplay . $_POST['MM'] . "月";
    if (@$_POST['DD']) {
        @$time = $time . "-" . $_POST['DD'];
        @$diasplay = $diasplay . $_POST['DD'] . "日";
    }
}
if ($tchNum!=null&&$tchNum!='请输入教师工号') {
    echo "<p>当前查询工号为 <span style='background-color: lightgreen ;'>" . $tchNum . "</span> 的老师在 <span style='background-color: lightgreen ;'>" . $diasplay . "</span>的操作记录</p>";
}
$sql = "select * from log where tchNum='$tchNum' and time LIKE '%$time%'";
$result = mysqli_query($con, $sql);
?>
<textarea rows="25" cols="70" readonly="readonly">
    <?php
    echo "++++操作时间++++++工号++++姓名++++操作教室++++++++\n";
    if ($time) {
        echo "结果如下:\n";
        if ($tchNum!=null&&$tchNum!='请输入教师工号') {
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
</body>
</html>