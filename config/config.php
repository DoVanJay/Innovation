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
$local_db_name = "control_system";//请不要修改


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


/**
 * 设置机房的开头名称
 * 例如：“微101”，“微102”，则填写“微”，
 *      “多媒体101”，“多媒体102”，则填写“多媒体”
 * 石油大学的机房命名规则为“微***”，“文理楼***”，所以在此填写开头名称“微”和“文理楼”
 * 请注意修改
 */
$computer_room_title = [
    "微",
    "文理楼"
];


/**
 * 设置对应网络状态的acl编号
 * $open_net_acl为完全开放网络的acl编号，在此统一为"#"，不要修改
 * $only_campus_acl为只允许访问校内网的acl编号
 * $shutdown_net_acl为完全关闭对外网络的acl编号
 * 请注意根据实际情况修改
 */
$open_net_acl = "#";//请千万不要修改
$only_campus_acl = "3102";//需根据实际情况修改
$shutdown_net_acl = "3101";//需根据实际情况修改