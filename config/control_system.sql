/*
本系统使用的是mysql数据库。

这是数据库配置语句，包含了项目所需的九张表，分别为：
admin，管理员身份表，adminID为管理员id，passwd为密码
tch，教师身份表，tchID为教师id，passwd为密码
course_timetable，课程数据表，
                  timeForClass为上课时间，该课程在本周周几的第几节课；
                  locationOfClass为上课地点，该课程在哪个教室上课；
                  tchID为上课的教师id；
                  detailsOfWeeks为上课的周次明细：该课程哪周有课；
the_first_day，记录开学第一天的数据表，
              year/month/day三个字段规定了本学期从哪天开始计算
operation_log，记录教师操作网络的日志，
              time为操作的时间，
              tchID为操作的教师id，
              classroomName为操作的教室对象，
              operation为具体的操作是什么；
classroom_info，教室信息表，
              classroom_name为教室名，
              vlan为教室在所归属的交换机中对应的vlan，
              current_acl_num为当前教室在所归属的交换机中对应的vlan现在使用的acl编码，
              switch_ip为教室网络所属的交换机的ip；
switch_info，交换机信息表，
            switch_ip为汇聚交换机的ip，
            passwd为telnet连接交换机的密码；
messages，通知消息表，message为通知全员的消息；
restore_network_schedule，在课程结束后恢复被关闭网络教室的网络为开放状态
                        classroom_vlan为教室在所归属的交换机中对应的vlan，
                        current_acl_num为当前教室在所归属的交换机中对应的vlan现在使用的acl编码，
                        switch_ip,为教室网络所属的交换机的ip，
                        endTimestamp，课程结束时间的时间戳
*/




-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-09-14 00:32:13
-- 服务器版本： 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `control_system`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `adminID` varchar(100) NOT NULL,
  `passwd` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `adminID`, `passwd`) VALUES
(1, '1507020325', '$2a$08$ncxQT2HNtFx2TTJjJqMrR.3KzQv7a0KGlI5BFvsMU8kw3f41bVppG');

-- --------------------------------------------------------

--
-- 表的结构 `classroom_info`
--

CREATE TABLE `classroom_info` (
  `id` int(11) NOT NULL,
  `classroom_name` varchar(100) NOT NULL,
  `vlan` varchar(100) NOT NULL,
  `current_acl_num` varchar(100) NOT NULL,
  `switch_ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `classroom_info`
--

INSERT INTO `classroom_info` (`id`, `classroom_name`, `vlan`, `current_acl_num`, `switch_ip`) VALUES
(1, '测试：微101', '200', '#', '10.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `course_timetable`
--

CREATE TABLE `course_timetable` (
  `id` int(11) NOT NULL,
  `timeForClass` varchar(100) NOT NULL,
  `locationOfClass` varchar(100) NOT NULL,
  `tchID` varchar(100) NOT NULL,
  `detailsOfWeeks` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `operation_log`
--

CREATE TABLE `operation_log` (
  `id` int(11) NOT NULL,
  `time` varchar(100) NOT NULL,
  `tchID` varchar(100) NOT NULL,
  `classroomName` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `operation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `restore_network_schedule`
--

CREATE TABLE `restore_network_schedule` (
  `id` int(11) NOT NULL,
  `classroom_vlan` varchar(100) NOT NULL,
  `current_acl_num` varchar(100) NOT NULL,
  `switch_ip` varchar(100) NOT NULL,
  `endTimestamp` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `switch_info`
--

CREATE TABLE `switch_info` (
  `id` int(11) NOT NULL,
  `switch_ip` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `switch_info`
--

INSERT INTO `switch_info` (`id`, `switch_ip`, `passwd`) VALUES
(1, '10.0.0.1', '123456');

-- --------------------------------------------------------

--
-- 表的结构 `tch`
--

CREATE TABLE `tch` (
  `id` int(11) NOT NULL,
  `tchID` varchar(100) NOT NULL,
  `passwd` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tch`
--

INSERT INTO `tch` (`id`, `tchID`, `passwd`) VALUES
(1, '1507020326', '$2a$08$QUDWdmguleParpEMyzkfuuFkUwBxYXb1/3qVHS/ODsf5WnNXZ6/BW');

-- --------------------------------------------------------

--
-- 表的结构 `the_first_day`
--

CREATE TABLE `the_first_day` (
  `year` varchar(100) NOT NULL,
  `month` varchar(100) NOT NULL,
  `day` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `the_first_day`
--

INSERT INTO `the_first_day` (`year`, `month`, `day`) VALUES
('2017', '09', '04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classroom_info`
--
ALTER TABLE `classroom_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classroom_name` (`classroom_name`);

--
-- Indexes for table `course_timetable`
--
ALTER TABLE `course_timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_log`
--
ALTER TABLE `operation_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restore_network_schedule`
--
ALTER TABLE `restore_network_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `switch_info`
--
ALTER TABLE `switch_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `switch_ip` (`switch_ip`);

--
-- Indexes for table `tch`
--
ALTER TABLE `tch`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `classroom_info`
--
ALTER TABLE `classroom_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `course_timetable`
--
ALTER TABLE `course_timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `operation_log`
--
ALTER TABLE `operation_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- 使用表AUTO_INCREMENT `restore_network_schedule`
--
ALTER TABLE `restore_network_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- 使用表AUTO_INCREMENT `switch_info`
--
ALTER TABLE `switch_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `tch`
--
ALTER TABLE `tch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
