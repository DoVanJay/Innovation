<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>修改密码</title>
</head>
<body>
<div align="center"><img src="/imgs/title.png" width="550"/>
</div>


<div class="input-group" style="width:300px;margin-left: 32%;margin-top:8%">
    <div style="background-color: rgba(255, 255, 255, 0.4);padding: 20px;">
        <span style="color: gray;font-size: 130%;font-family: 幼圆, serif;">修改密码:</span><br><br>
        <form class="bs-example bs-example-form" name="login" method="post" id="login"
              action="">
            <input class="form-control" type="text" name="passwd"
                   placeholder="新密码"/><br><br>
            <button type="submit" class="btn btn-warning" style="width: 130px;height: 35px;margin-left: 50%;">修改密码
            </button>
        </form>
        <button onclick='history.back()' class="btn btn-success"
                style="width: 130px;height: 35px;margin-left: 50%;">返回上页
        </button>
    </div>
</div>

<?php
@session_start();
include("../possess/mysql.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['status'] == "admin") {
        $sql = 'update admin set passwd= "' . $_POST["passwd"] . '" where adminID=' . $_SESSION["ID"];
    } elseif ($_SESSION['status'] == "tch") {
        $sql = 'update tch set passwd= "' . $_POST["passwd"] . '" where tchID=' . $_SESSION["ID"];
    }
    $result = mysqli_query($con, $sql);
    if (mysqli_affected_rows($con)>0) {
        echo "<script>alert('修改成功');</script>";
    }
}
?>
</body>
</html>
