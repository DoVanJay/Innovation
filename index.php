<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div align="center"><img src="../head.jpg" width="550"/>
</div>
<br>
<div align="left" style="margin-left: 20%">
    <form name="login" method="post" id="login" action="/possess/login.php">
        <table>
            <tr>
                <td>
                    username:<input type="text" id="input1" name="ID"/><br><br>
                </td>
            </tr>
            <tr>
                <td>
                    password:<input type="password" id="input2" name="passwd"/>
                </td>
            </tr>
            <tr>
                <td>
                    <div align="left" style="margin-left: 85%">
                        <button type="submit" class="btn btn-warning">提交</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
