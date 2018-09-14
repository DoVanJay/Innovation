NCS(Network Control System)
==================

### 简单试运行
如果你需要试运行该系统，请使用php命令为系统启动php内置的服务器：
```
php -S localhost:8081 -t C:\Users\jay\Documents\CODE\IDEA-Projects\Innovation
```
从浏览器访问 http://localhost:8081 进入系统
此时是使用密码登录的方式
请事先参照下面的”关于数据库“的提示来导入数据库。
管理员测试登录账号：1507020325，密码：yq
教师测试登录账号：1507020326，密码：dwj


### 更换界面背景和学校徽标
界面背景和学校徽标均是以本地图片的形式保存在imgs文件夹中，bg.jpg为背景图片，title.png为”徽标+系统名“图片，
直接替换并修改为对应的名字即可


### 关于数据库
本系统使用的是mysql数据库。
请运行./possess/create-database.php来导入创建数据库
./config/control_system.sql是导出的数据库配置语句，包含了项目所需的九张表，分别为：
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

### 使用方法
系统功能的使用方法在相应的页面下方都有提示

### 登录系统的方式:
如使用单点登录方式请完善系统根目录下的SSO.php文件；
如果不需要删除使用密码登录方式，请在该工程里全局搜索“如果不使用密码登录方式”，按照提示进行代码修改；

### 全局配置文件
请按照其中的提示手动配置./config/config.php文件



### 计划任务
请将./schedule文件夹中的文件加入操作系统的计划任务（windows为”计算机管理“中的”系统工具/任务计划程序“，linux为contrab）
DB-update.php使用php命令在每天的凌晨一点运行，
restore-network.php使用php命令每三分钟运行一次；

其中DB-update.php文件为自动更新数据库课程数据表的脚本文件，其中只实现了远程服务器为Oracle的方法，
如果您的远程服务器使用的其他型号的数据库，请根据实际情况修改后再运行。

### 关于匹配上课时间和周次明细的规则
系统中默认的是石油大学的规则，
上课时间的规则是”3091011“，第一个数字为周几，后面的数字为当天的第几节课，
周次明细的规则是”1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16“，即第1，2，3，……，16周有课
如果需要修改规则，则需要修改tch.php中的代码。
该系统的后续版本会完善规则的设置功能

### 致歉
由于开发的时间紧迫，本系统还有很多不完善和存在漏洞的地方，在后续的版本中会不断修正和完善，敬请谅解。
使用过程中如遇到问题请邮件联系：1507020326@s.upc.edu.cn


### 注意
如果运行系统时php提示PHP Warning:system() has been disabled for security reasons in create-database.php on line 20
之类的消息，请到php配置文件（php.ini）中将system函数从禁止的函数中删掉，否则由于安全策略无法调用

*ps.详细用户手册请从此[链接](http://owncloud.safeandsound.cn:8081/index.php/s/T9B0uGKWFefcHt6)下载*
