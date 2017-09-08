<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>设置全员通知消息</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div align="center">
    <img src="/imgs/title.png" style="width:550px"/>
</div>
<p style="margin-left: 30%;margin-top: 50px">请在下面输入你想通知全体使用人员的消息：</p>
<form action="admin-setMessages.php" method="post" onsubmit="return confirm('确认提交？');">
    <textarea id="messages" class="messages" name="messages"></textarea>
    <div class="btn-group" style="margin-left: 55%;margin-top: 10px">
        <button type="submit" class="btn btn-warning" style="height: 50px;width: 90px">提交</button>
        <button type="button" class="btn btn-success" style="height: 50px;width: 90px;" onclick="window.location.href='admin.php'">返回主界面</button>
    </div>
</form>

<div class="bottom-remind">
<pre>
<span>注意：</span>
1.设置后将在教师和管理员操作界面首页出现“通知：****”的滚动显示字样；
2.目前只能设置一条通知；
</pre>
</div>
</body>
</html>

<?php
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/7/2017
 * Time: 10:00 PM
 */
require "../possess/mysql.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messages = $_POST["messages"];
    $clean_messages_sql = "truncate messages";
    mysqli_query($con, $clean_messages_sql);
    $store_messages_sql = "insert into messages(message) VALUES('" . $messages . "');";
    $result = mysqli_query($con, $store_messages_sql);
    if ($result) {
        echo "<script>alert('设置通知消息成功');</script>";
    }

}

