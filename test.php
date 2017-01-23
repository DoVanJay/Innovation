<?php
//date_default_timezone_set("PRC");
//echo date('H:i');
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test</title>
    <script>
        var chk = 0;
        window.onload = function () {
            var chkObjs = document.getElementsByName("radio");
            for (var i = 0; i < chkObjs.length; i++) {
                if (chkObjs[i].checked) {
                    chk = i;
                    break;
                }
            }
        }

        function check_radio() {
            var chkObjs = document.getElementsByName("radio");
            for (var i = 0; i < chkObjs.length; i++) {
                if (chkObjs[i].checked) {
                    if (chk == i) {
                        alert("radio值没有改变不能提交");
                        break;
                    }
                }
            }
        }
    </script>

</head>
<body>
<form action="" method="get" onsubmit='javascript:return check_radio()'>
        <pre style="font-size:150%">
设置学生机网络状态（设置后将会记住选项以表示当前状态）：
             <label><input name="network" type="radio" value="0"/>完全开放 </label>
             <label><input name="network" type="radio" value="1" />仅关闭外网</label>
             <label><input name="network" type="radio" value="2" />完全关闭（包括内网）</label>
                                 <input name="" type="submit" value="提交"/>
        </pre>
</form>

</body>
</html>
