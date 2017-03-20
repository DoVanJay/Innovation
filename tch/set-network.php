<?php
/**
 * 设置网络状态
 */
/** to be continue
 * TTTTTTTTTTTTTTTTTTTTTTT         BBBBBBBBBBBBBBBBB                   CCCCCCCCCCCCC
 * log 中写入教师工号
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
session_start();
header("content-type:text/html;charset=utf-8");
$host = "localhost";
$username = "root";
$password = "";
$db = "operating_log";
$con = mysqli_connect($host, $username, $password, $db);
if (mysqli_connect_errno()) {
    echo "连接失败";
    exit();
}
if ($_POST['network'] != 0 && $_POST['network'] != 1 && $_POST['network'] != 2) {
    echo "<script>alert('对不起,操作出错！')</script>";
} else {
    $var = $_POST["network"];
    switch ($_POST['network']) {
        case 0:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态////////////////////
            ///////////////////////////////////////////////////
            $sql = "insert log values(NOW(),\"" .$_SESSION["tchID"]. $_SESSION["username"] . "\",\"" . $_POST['classroomName'] . "\",\"完全开放\")";
            mysqli_query($con, $sql);
            break;
        case 1:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态//////////////////////
            ///////////////////////////////////////////////////
            $sql = "insert log values(NOW(),\"".$_SESSION["tchID"] . $_SESSION["username"] . "\",\"" . $_POST['classroomName'] . "\",\"仅关闭内网\")";
            mysqli_query($con, $sql);
            break;
        case 2:
            ///////////////////////////////////////////////////
            ///这里设置交换机对应接口下的网络状态//////////////////////
            ///////////////////////////////////////////////////
            $sql = "insert log values(NOW(),\"" .$_SESSION["tchID"]. $_SESSION["username"] . "\",\"" . $_POST['classroomName'] . "\",\"完全关闭\")";
            mysqli_query($con, $sql);
            break;
    }
}
header("location:teacher.php");