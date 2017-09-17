<?php
/**
 * 实现 临时换教室功能(仅限当天修改当天有效) 功能
 */
require('../possess/mysql.php');
require('../function/function.php');

$class1 = null;
$class2 = null;
$classLocation = null;
$tchID = null;
@$class1 = $_POST['no1'];
@$class2 = $_POST['no2'];
@$classLocation = $_POST['classLocation'];
@$tchID = $_POST['tchID'];
$today = date('y-m-d');
$day = array('日', '一', '二', '三', '四', '五', '六');
$firstDay = mysqli_fetch_array(mysqli_query($local_con, 'select * from the_first_day'));
$firstDay = $firstDay[0] . '-' . $firstDay[1] . '-' . $firstDay[2];
$days = calDays($firstDay, $today);      /*当天和本学期第一天中间隔了多少天*/
$whichWeek = whichWeek($days);          /*当前是第几周*/
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
$sql_check = "select tchID from course_timetable  
                      where tchID='$tchID'
                      and (SUBSTRING(timeForClass,2) LIKE '%$classes%' or locate(SUBSTRING(timeForClass,2),'$classes')) /*查找包含 classes 或被 classes包含的*/
                      and LEFT (timeForClass,1)='$dayInWeek'/*确定周几相同*/
                      and find_in_set('$whichWeek',detailsOfWeeks); ";
//设置的时间有无课,有的话直接修改,否则添加
$check_result = mysqli_query($local_con, $sql_check);
@$check = mysqli_num_rows($check_result);

if ($class1 != null && $class2 != null && $classLocation != null && $tchID != null) {
    if ($check && $class1 != null) {
        $sql_update = "update course_timetable 
                          set locationOfClass='$classLocation' ,timeForClass='$dayInWeek$classes' 
                          where tchID='$tchID' 
                          and (timeForClass LIKE '%$classes%' or locate(timeForClass,'$dayInWeek$classes'))  
                          and find_in_set('$whichWeek',detailsOfWeeks)";
        if (mysqli_query($local_con, $sql_update)) {
            echo "<script>alert('更新操作成功');
                          window.location.href='admin-setClass.php';
                  </script>";
        } else {
            echo "<script>alert('更新操作失败');
                          window.location.href='admin-setClass.php';
                  </script>";
        }
    } else {
        if (!($class1 == 'n' || $class2 == 'm' || $classLocation == '教室位置' || $tchID == '请输入教师工号')) {
            $sql_insert = "insert into course_timetable(timeForClass,locationOfClass,tchID,detailsOfWeeks) 
                                values('$dayInWeek$classes','$classLocation','$tchID','$whichWeek')";
            if (mysqli_query($local_con, $sql_insert)) {
                echo "<script>alert('插入操作成功');
                              window.location.href='admin-setClass.php';
                      </script>";
            } else {
                echo "<script>alert('插入操作失败');
                              window.location.href='admin-setClass.php';
                      </script>";
            }
        } else if ($class1 == 'n' || $class2 == 'm' || $classLocation == '教室位置' || $tchID == '请输入教师工号') {
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
</head>
<script>
    //    classLocation = document.classroom.classLocation.outerHTML;
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


</script>
<body>
<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<br>
<div align="left" class="main">
    <p>
        请输入上课时间,教室名称以及教师工号:
    </p>
    <form name="classroom" method="post" onsubmit="return confirm('确认提交？')" action="admin-setClass.php">
        <p style='display: inline'>第<select name="no1" onchange="setClassNo2(this.value)">
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
                <!--                <option value="微">微</option>-->
                <!--                <option value="文理">文理</option>-->
                <?php
                $query_classroom_sql = "select classroom_name from classroom_info;";
                $result = mysqli_query($local_con, $query_classroom_sql);
                $classroom_names = mysqli_fetch_all($result);

                foreach ($classroom_names as $item) {
                    echo "<option value=$item[0]>$item[0]</option>";
                }
                ?>
            </select>
            <!--            <select name="classNum">-->
            <!--                <option value="num">教室编号</option>-->
            <!--            </select>-->
            <input name="tchID" type="text" value="请输入教师工号"
                   onfocus="javascript:if(this.value=='请输入教师工号')this.value='';">
        </p>
        <div class="btn-group" style="display: inline-block;">
            <button type="submit" class="btn btn-warning">提交</button>
            <button type="button" class="btn btn-success" onclick="window.location.href='admin.php'">点此返回主操作界面</button>
        </div>
    </form>

</div>
<div class="bottom-remind">
<pre>
<span>注意：</span>
1.由于服务器每天凌晨会从教务处拉取更新课表,所以该设置仅限于当天修改当天有效,隔天无效;
2."微"开头教室为多媒体机房,"文理"开头教室为文理楼机房;
4.具体使用规则举例如下：
  例如，老师在第01020304节有课，可以修改为任意包含01020304的区间，如010203040506节；
  或任意被01020304包含的区间，如0102节，但不能修改为03040506，否则会与原01020304中的0304冲突
  老师在要安排的时间段内无课，则可以任意添加；
  <a style="color: red;font-weight: bold">如果有必要可以先将指定教师当天有冲突的机房课程删除，再进行重新添加。</a>
</pre>
</div>

</body>
</html>
