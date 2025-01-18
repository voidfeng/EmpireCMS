<?php
require("../class/connect.php");
require("../class/q_functions.php");
require("../member/class/user.php");
eCheckCloseMods('member');//关闭模块
eCheckCloseMods('mconnect');//关闭模块
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMemberConnect();//验证开启的接口
include('memberconnectfun.php');
//接口
$apptype=RepPostVar($_GET['apptype']);
$appr=$empire->fetch1("select * from {$dbtbpre}enewsmember_connect_app where apptype='$apptype' and isclose=0".do_dblimit_one());
if(!$appr['id'])
{
	printerror2('请选择登录方式','');
}
$ReturnUrlQz=eReturnDomainSiteUrl();
eCheckStrType(4,$appr['apptype'],1);
$file=$appr['apptype'].'/to_login.php';
@include($file);
db_close();
$empire=null;
?>