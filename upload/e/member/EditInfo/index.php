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
$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='".$user['userid']."'".do_dblimit_one());
$formid=GetMemberFormId($user['groupid']);
$formid=(int)$formid;
$formfile='../../../c/ecachemod/emodpub/memberform'.$formid.'.php';
if(eDoCheckHvFile($formfile)==0)
{
	exit();
}
//导入模板
include(ECMS_PATH.'e/template/member/EditInfo.php');
db_close();
$empire=null;
?>