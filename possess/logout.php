<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>教师操作界面</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<?php
/**
 * 用户注销，销毁session
 */
session_start();
session_unset();
session_destroy();
echo "<div class='translucence' style='margin-top:8%;padding: 20px;width:300px;margin-left: 32%;border-radius:10px;'>
            <span class='title'>友情提醒:</span><br><br>
            您已注销<br/>
            点此 <a href='../index.php'>重新登录</a><br />
      </div>";
?>
</body>
</html>
