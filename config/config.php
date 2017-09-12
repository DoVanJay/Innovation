<?php
/**
 * Created by IntelliJ IDEA.
 * User: jay
 * Date: 9/10/2017
 * Time: 5:02 PM
 */

/**
 * 本地数据库配置
 */
$localhost = "localhost";
$local_db_user_name = "root";
$local_db_user_password = "";
$local_db_name = "control_system";


/**
 * 远程数据库配置
 */
//远程服务器地址
$remote_db_host = "";
//远程数据库用户名
$remote_db_user_name = "";
//远程数据库用户密码
$remote_db_user_password = "";
//远程课程数据表所在的数据库名
$remote_db_name = "";
//远程课程数据表名
$remote_table_name = "";

//下面定义远程课程数据表中的各项字段名
//教师工号字段
$remote_tchID_colName = "";
//上课时间（第几节课）字段
$remote_timeForClass_colName = "";
//上课地点字段
$remote_locationOfClass_colName = "";
//上课周次（第几周有课）字段
$remote_detailsOfWeeks_colName = "";
