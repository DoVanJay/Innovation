<?php
/**
 * 用以从主服务器更新课表数据
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/4/2017
 * Time: 11:51 AM
 */
//远程服务器地址
$remote_db_host = "";
//数据库用户名
$db_user = "";
//数据库用户密码
$db_user_password = "";
//课程数据表所在的数据库名
$db_name = "";
//课程数据表名
$table_name = "";


//下面定义课程数据表中的各项字段名

//教师工号字段
$tchID_colName = "";
//上课时间（第几节课）字段
$timeForClass_colName = "";
//上课地点字段
$locationOfClass_colName = "";
//上课周次（第几周有课）字段
$detailOfWeeks_colName = "";


$getDate_sql = "select ";
