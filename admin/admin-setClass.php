<?php
/**
 * 实现 [临时换教室功能(仅限当天修改当天有效)] 功能
 */
/** to be continue
 * TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 *直接填写文本框,后台数据格式化提交到数据库直接修改对应课的操作教室
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

require('../possess/mysql.php');
$class1 = null;
$class2 = null;
$classLocation = null;
$classNum = null;
$tchID = null;
@$class1 = $_POST['no1'];
@$class2 = $_POST['no2'];
@$classLocation = $_POST['classLocation'];
@$classNum = $_POST['classNum'];
@$tchID = $_POST['tchID'];
//echo $class1 . "-" . $class2 . "-" . $classLocation . "-" . $classNum . "-" . $tchID;
$today = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($con, 'select * from thefirstday'));
$firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];
$days = calDays($firstDay, $today);      /*当天和本学期第一天中间隔了多少天*/
$whichweek = whichWeek($days);          /*当前是第几周*/
if (date("w") != 0) {
    $dayInWeek = date("w");         /*对当前是周几的判断*/
} else {
    $dayInWeek = 7;
}
//处理class1到class2之间的课补足
$i = $class1;
$classes = null;
for (; $i <= $class2; $i++) {
    if ($i < 10) {
        $classes = $classes . '0' . $i;
    } else {
        $classes = $classes . $i;
    }
}
//and (locationOfClass like '文理%' or locationOfClass like '微%')
@$sql_check = "select tchID from schedule 
                      where tchID='$tchID'
                      and (SUBSTRING(timeForClass,2) LIKE '%$classes%' or locate(SUBSTRING(timeForClass,2),'$classes')) /*查找包含 classes 或被 classes包含的*/
                      and LEFT (timeForClass,1)='$dayInWeek'/*确定周几相同*/
                      and find_in_set('$whichweek',detailsOfWeeks); ";
//设置的时间有无课,有的话直接修改,否则添加
$check_result = mysqli_query($con, $sql_check);
$check = mysqli_num_rows($check_result);
/** to be continue
 * TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 *目前设置临时教室功能对于设置上课时间还只能扩大或缩小，不能实现错开来，
 * 如0102节要换到0203节的话就无法合并为010203，或将0102节的安排删掉，只能0102和0203同时存在
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
if ($class1 != null && $class2 != null && $classLocation != null && $classNum != null && $tchID != null) {
    if ($check && $class1 != null) {
        @$sql_update = "update schedule
                          set locationOfClass='$classLocation$classNum' ,timeForClass='$dayInWeek$classes' 
                          where tchID='$tchID' 
                          and (timeForClass LIKE '%$classes%' or locate(timeForClass,'$dayInWeek$classes'))  
                          and find_in_set('$whichweek',detailsOfWeeks)";
        if (@mysqli_query($con, $sql_update)) {
            echo "<script>alert('更新操作成功');
                          window.location.href='admin-setClass.php';
                  </script>";
        } else {
            echo "<script>alert('更新操作失败');
                          window.location.href='admin-setClass.php';
                  </script>";
            header("location:admin-setClass.php");
        }
    } else {
        if (!($class1 == 'n' || $class2 == 'm' || $classLocation == '教室位置' || $classNum == 'num' || $tchID == '请输入教师工号')) {
            @$sql_insert = "insert into schedule(timeForClass,locationOfClass,tchID,detailsOfWeeks) 
                                values('$dayInWeek$classes','$classLocation$classNum','$tchID','$whichweek')";
            if (@mysqli_query($con, $sql_insert)) {
                echo "<script>alert('插入操作成功');
                              window.location.href='admin-setClass.php';
                      </script>";
            } else {
                echo "<script>alert('插入操作失败');
                              window.location.href='admin-setClass.php';
                      </script>";
            }
        } else if ($class1 == 'n' || $class2 == 'm' || $classLocation == '教室位置' || $classNum == 'num' || $tchID == '请输入教师工号') {
            echo "<script>alert('请填入完整信息');</script>";
        }
    }
}
?>
<!--suppress ALL -->
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>设置教师定时定点操作权限</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<script>
    classLocation = document.classroom.classLocation.outerHTML;
    classNum = document.classroom.classNum.outerHTML;
    function setClassNo2(str) {
        var s = '<option>m</option>';
        var i = str;
        do {
            i++;
            if (i < 10) {
                s += "<option  value='" + i + "'> " + 0 + i + "</option>\r\n";
            }
            else {
                s += "<option  value='" + i + "'> " + i + "</option>\r\n";
            }
        } while (i < 11);
        document.classroom.no2.outerHTML = "<select name='no2'>" + s + "</select>";
    }

    function setLocation(str) {
        var s = '<option value="num">教室编号</option>';
        if (str != "微") {
            if (str == "文理") {
                for (var i = 10; i < 20; i++) {
                    s += "<option  value='" + i + "'> " + i + "</option>\r\n";
                }
                document.classroom.classNum.outerHTML = "<select name='classNum'>" + s + "</select>";
            }
            else {
                document.classroom.classNum.outerHTML = '<select name="classNum"><option value="num">教室编号</option></select>';
            }
        } else {
            for (var i = 1; i < 10; i++) {
                s += "<option value='" + i + "'> " + i + "</option>\r\n";
            }
            document.classroom.classNum.outerHTML = "<select name='classNum'>" + s + "</select>";
        }
    }
</script>
<body>
<div align="center"><img src="/imgs/title.png" width="550"/>
</div>
<br>
<div align="left" style="margin-left: 20%">
    <p>
        请输入上课时间,教室名称以及教师工号:
    </p>
    <form name="classroom" method="post" onsubmit="return confirm('确认提交？')" action="admin-setClass.php">
        <p>第<select name="no1" onchange="setClassNo2(this.value)">
                <option>n</option>
                <option value="1">01</option>
                <option value="2">02</option>
                <option value="3">03</option>
                <option value="4">04</option>
                <option value="5">05</option>
                <option value="6">06</option>
                <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
            </select>&nbsp;-&nbsp;
            <select name="no2">
                <option>m</option>
            </select>节课&nbsp;&nbsp;
            <select name="classLocation" onchange="setLocation(this.value)">
                <option>教室位置</option>
                <option value="微">微</option>
                <option value="文理">文理</option>
            </select>
            <select name="classNum">
                <option value="num">教室编号</option>
            </select>
            <!--to be continue-->
            <!--TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC-->
            <!-- 教室编号以及对应地址ip       -->
            <!--T:::::::::::::::::::::T         B::::::BBBBBB:::::B            CC:::::::::::::::C-->
            <!--TTTTTT  T:::::T  TTTTTT           B::::B     B:::::B          C:::::C       CCCCCC-->
            <!--        T:::::T                   B::::B     B:::::B         C:::::C-->
            <!--        T:::::T                   B::::BBBBBB:::::B         C:::::C-->
            <!--        T:::::T                   B:::::::::::::BB          C:::::C-->
            <!--        T:::::T                   B::::BBBBBB:::::B         C:::::C-->
            <!--        T:::::T                   B::::B     B:::::B        C:::::C-->
            <!--        T:::::T                   B::::B     B:::::B        C:::::C-->
            <!--        T:::::T                   B::::B     B:::::B         C:::::C       CCCCCC-->
            <!--      TT:::::::TT               BB:::::BBBBBB::::::B          C:::::CCCCCCCC::::C-->
            <!--      T:::::::::T               B:::::::::::::::::B            CC:::::::::::::::C-->
            <!--      T:::::::::T               B::::::::::::::::B               CCC::::::::::::C-->
            <!--      TTTTTTTTTTT               BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC-->
            <input name="tchID" type="text" value="请输入教师工号"
                   onfocus="javascript:if(this.value=='请输入教师工号')this.value='';">
            <button type="submit" class="btn btn-warning">提交</button>
            <button type="button" class="btn btn-success" onclick="window.location.href='admin.php'">点此返回主操作界面</button>
    </form>

</div>
<div class="bottom-remind">
<pre>
<span>注意：</span>
1.由于服务器每天凌晨会从教务处拉取更新课表,所以该设置仅限于当天修改当天有效,隔天无效;
2."微"开头教室为多媒体机房,"文理"开头教室为文理楼机房;
3.具体使用规则举例如下：
  例如，老师在第01020304节有课，可以修改为任意包含01020304的区间，如010203040506节；
  或任意被01020304包含的区间，如0102节，但不能修改为03040506，否则会与原01020304中的0304冲突
  老师在要安排的时间段内无课，则可以任意添加
</pre>
</div>

</body>
</html>
