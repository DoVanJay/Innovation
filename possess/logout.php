<?php
/**
 * 用户注销，销毁session
 */
session_start();
session_unset();
session_destroy();
header("location:login.php");