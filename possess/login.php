<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<?php
/**
 * 登录功能
 */
session_start();
require('./mysql.php');
@$_SESSION['ID'] = $_POST['ID'];
@$_SESSION['passwd'] = $_POST['passwd'];
$userID = $_SESSION['ID'];
$passwd = $_SESSION['passwd'];
$sql_tch = "select * from tch where passwd='$passwd' and tchID='$userID';";
$sql_admin = "select * from admin where passwd='$passwd' and adminID='$userID';";
$tch_result = mysqli_query($con, $sql_tch);
$admin_result = mysqli_query($con, $sql_admin);
$tch_row = mysqli_num_rows($tch_result);       /*查找是否有符合的用户*/
$admin_row = mysqli_num_rows($admin_result);         /*查找是否有符合的用户*/
if ($tch_row == 0 && $admin_row == 0) {
    echo "<div class='translucence' style='margin-top:8%;padding: 20px;width:300px;margin-left: 32%;border-radius:10px;'>
            <span class='title'>友情提醒:</span><br><br>
            您已注销或登录失败<br/>
            点此 <a href='../index.php'>重新登录</a><br />
          </div>";
} else {
    if ($admin_row != 0) {
        header("location:../admin/admin.php");
    } else if ($tch_row != 0) {
        header("location:../tch/teacher.php");
    }
} ?>
</body>
</html>
