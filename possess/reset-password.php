<?php
/**
 * 当使用单点登录等集成验证登录方式时，该页面无存在意义
 */
@session_start();
require "../possess/mysql.php";
require "../possess/PasswordHash.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hasher = new PasswordHash(8, FALSE);
    if ($_SESSION["status"] == "admin") {
        $old_passwd_hash_query = mysqli_query($con, "select passwd from admin where adminID='" . $_SESSION['ID'] . "';");
    } else {
        $old_passwd_hash_query = mysqli_query($con, "select passwd from tch where tchID='" . $_SESSION['ID'] . "';");
    }

    $old_passwd_hash = mysqli_fetch_array($old_passwd_hash_query);

    if ($hasher->CheckPassword($_POST["old-passwd"], $old_passwd_hash[0])) {
        $hash_password = $hasher->HashPassword($_POST["new-passwd"]);
        if ($_SESSION['status'] == "admin") {
            $sql = 'update admin set passwd= "' . $hash_password . '" where adminID=' . $_SESSION["ID"];
        } elseif ($_SESSION['status'] == "tch") {
            $sql = 'update tch set passwd= "' . $hash_password . '" where tchID=' . $_SESSION["ID"];
        }
        $result = mysqli_query($con, $sql);
        if (mysqli_affected_rows($con) > 0) {
            echo "<script>alert('修改成功');</script>";
        }
    } else {
        echo "<script>alert('原密码错误，修改失败');</script>";
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--    <link rel="stylesheet" href="/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="/css/style.css">
    <title>修改密码</title>
</head>
<body>
<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<div class="input-group" style="width:300px;margin-left: 32%;margin-top:8%">
    <div style="background-color: rgba(255, 255, 255, 0.4);padding: 20px;border-radius:10px;">
        <span style="color: gray;font-size: 130%;font-family: 幼圆, serif;">修改密码:</span><br><br>
        <form class="bs-example bs-example-form" name="login" method="post" id="login"
              action="" onsubmit="return confirm_update()">
            <input class="form-control" type="password" name="old-passwd"
                   placeholder="原密码"/><br>
            <input class="form-control" type="password" name="new-passwd" id="new"
                   placeholder="新密码"/><br>
            <input class="form-control" type="password" name="confirm-passwd" id="con"
                   placeholder="确认新密码"/><br>
            <button type="submit" class="btn btn-warning"
                    style="width: 130px;height: 35px;margin-left: 50%;">修改密码
            </button>
        </form>
        <button onclick="back()" class="btn btn-success"
                style="width: 130px;height: 35px;margin-left: 50%;">返回主界面
        </button>
    </div>
</div>
<script>
    function confirm_update() {
        if (confirm("确认修改密码？")) {
            if (document.getElementById("new").value === document.getElementById("con").value) {
                return true;
            } else {
                alert("新密码与确认密码不一致，请重新填写");
                return false;
            }
        } else {
            return false;
        }
    }

    function back() {
        if ('<?php echo $_SESSION["status"]?>' == "tch") {
            window.location = "../tch/tch.php";
        } else {
            window.location = "../admin/admin.php";
        }
    }
</script>

</body>
</html>