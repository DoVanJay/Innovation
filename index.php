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
    <img src="/imgs/title.png" width="550px"/>
</div>


<form style="margin-top:8%" class="bs-example bs-example-form" name="login" method="post" id="login" action="/possess/login.php">
    <div class="input-group" style="width:300px;margin-left: 32%">
        <div style="background-color: rgba(255, 255, 255, 0.4);padding: 20px;">
            <span style="color: gray;font-size: 130%;font-family: 幼圆, serif;">登录系统:</span><br><br>
            <input class="form-control" type="text" name="ID"
                   placeholder="用户名"/><br>
            <input class="form-control" type="password" name="passwd"
                   placeholder="密码"/>
            <br>
            <button type="submit" class="btn btn-warning" style="width: 130px;height: 50px;margin-left: 50%;">登录
            </button>
        </div>
    </div>
</form>


</body>
</html>
