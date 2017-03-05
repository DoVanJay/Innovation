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
require('../possess/mysql.php');

?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set Classroom</title>
</head>
<script>
    classLocation = document.classroom.classLocation.outerHTML;
    classNum = document.classroom.classNum.outerHTML;
    function setLocation(str) {
        var s = '<option value="num">教室编号</option>';
        if (str == "wei") {
            for (var i = 1; i < 10; i++) {
                s += "<option value='" + i + "'> " + i + "</option>\r\n";
            }
            document.classroom.classNum.outerHTML = "<select name='classNum'>" + s + "</select>";
        } else if (str == "wenli") {
            for (var i = 10; i < 20; i++) {
                s += "<option value='" + i + "'> " + i + "</option>\r\n";
            }
            document.classroom.classNum.outerHTML = "<select name='classNum'>" + s + "</select>";
        }
        else {
            document.classroom.classNum.outerHTML = '<select name="classNum"><option value="num">教室编号</option></select>';
        }
    }
</script>
<body>
<form name="classroom" method="post" action="admin-setClass.php">
    <select name="classLocation" onchange="setLocation(this.value)">
        <option>教室位置</option>
        <option value="wei">微</option>
        <option value="wenli">文理</option>
    </select>
    <select name="classNum">
        <option value="num">教室编号</option>
    </select>
    <input type="submit" value="提交">
</form>
</body>
</html>
