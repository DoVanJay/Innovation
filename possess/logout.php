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