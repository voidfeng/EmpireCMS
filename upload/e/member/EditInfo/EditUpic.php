<?php
require("../../class/connect.php");
require("../../class/q_functions.php");
require("../class/user.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
eCheckCloseMods('member');//关闭模块
$user=islogin();
$r=ReturnUserInfo($user['userid']);
//导入模板
include(ECMS_PATH.'e/template/member/EditUpic.php');
db_close();
$empire=null;
?>