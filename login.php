<?php
session_start();
require('./mysql.php');

//$_SESSION['username'] = '杜文杰';
//$_SESSION['passwd'] = 'dwjdwjdwj';
@$_SESSION['username'] = $_POST['name'];
@$_SESSION['passwd'] = $_POST['passwd'];

$username = $_SESSION['username'];
$passwd = $_SESSION['passwd'];

$sql_user = "select * from user where passwd='$passwd' and name='$username';";
$result[] = mysqli_query($con, $sql_user);
$row = mysqli_fetch_row($result[0]);         /*查找是否有符合的用户*/
if ($row == 0) {
    echo "<br/><br/>您已注销或登录失败。<br/><br/>点此&nbsp;&nbsp;&nbsp;<a href=\"index.php\">重新登录</a><br />";
} else {
    header("location:teacher.php");
}