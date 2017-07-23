<?php
//CAS Server的登陆URL
$loginServer = "http://cas.***.edu.cn/cas/login";
//CAS Server的验证URL
$validateServer = "http://cas.***.edu.cn/cas/serviceValidate";

//当前集成系统所在的服务器和端口号，服务器可以是机器名、域名或ip，建议使用域名。端口不指定的话默认是80
//以及新增加的集成登录入口
$thisOne = "http://localhost:8181/neusoftcas.php";

//判断是否有验证成功后需要跳转页面，如果有，增加跳转参数
if (isset($_REQUEST["redirectUrl"]) && !empty($_REQUEST["redirectUrl"])) {
    $thisOne = $thisOne . "?redirectUrl=" . $_REQUEST["redirectUrl"];
}

//判断是否已经登录
if (isset($_REQUEST["ticket"]) && !empty($_REQUEST["ticket"])) {
    //获取登录后的返回信息
    try {
        $validateurl = $validateServer . "?ticket=" . $_REQUEST["ticket"] . "&service=" . $thisOne;
        header("Content-Type:text/html;charset=utf-8");
        $validateResult = file_get_contents($validateurl);
        $validateResult = iconv("gb2312", "utf-8//IGNORE", $validateResult);
        //节点替换，去除sso:，否则解析的时候有问题
        $validateResult = preg_replace("/sso:/", "", $validateResult);

        $validateXML = simplexml_load_string($validateResult);
        //获取验证成功节点
        $successnode = $validateXML->authenticationSuccess[0];
        if (!empty($successnode)) {
            //获取用户账户
            $userid = $successnode->user;

            //实现集成系统的登录（需要集成系统开发人员完成）
            //............实现代码...................
            //实现登录完毕！

            //如果登录成功，执行下面代码，否则按集成系统业务逻辑处理
            //集成系统的首页URL
            $Rurl = "http://localhost:8181/index.php";
            if (isset($_REQUEST["redirectUrl"]) && !empty($_REQUEST["redirectUrl"])) {
                $Rurl = $_REQUEST["redirectUrl"];
            }
            //重定向浏览器
            header("Location: " . $Rurl);
            exit;
        } else {
            //重定向浏览器
            header("Location: " . $loginServer . "?service=" . $thisOne);
            //确保重定向后，后续代码不会被执行
            exit;
        }
    } catch (Exception $e) {
        echo "出错了";
        echo $e->getMessage();
    }
} else {
    //重定向浏览器
    header("Location: " . $loginServer . "?service=" . $thisOne);
    //确保重定向后，后续代码不会被执行
    exit;
}
?>