<?php
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 17-1-22
 * Time: 下午9:29
 */
session_start();
session_unset();
session_destroy();

header("location:login.php");
