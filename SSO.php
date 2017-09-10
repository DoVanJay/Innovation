<?php
/**
 * SINGLE SIGN ON
 * 单点登录文件
 *
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/10/2017
 * Time: 10:12 AM
 */
session_start();

//由于不同应用系统实现机制不一样，具体的单点登录由使用方实现
//
//
//
//
//在实现身份验证之后，必须将用户id信息写入session，即传给$_SESSION["ID"]变量
