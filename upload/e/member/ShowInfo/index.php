<?php
require("../../class/connect.php");
require("../../class/q_functions.php");
require("../../member/class/user.php");
require("../../data/dbcache/MemberLevel.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
eCheckCloseMods('member');//关闭模块
if($public_r['showinfolevel'])
{
	$user=islogin();
	$mvgresult=eMember_ReturnCheckMVGroup($user,$public_r['showinfolevel']);
	if($mvgresult<>'empire.cms')
	{
		printerror("NotLevelShowInfo","",1);
	}
}
$userid=(int)$_GET['userid'];
if($userid)
{
	$where=egetmf('userid')."='$userid'";
	$username='';
	$utfusername='';
}
else
{
	$username=RepPostVar($_GET['username']);
	if(empty($username))
	{
		printerror("NotUsername","",1);
	}
	$utfusername=$username;
	$where=egetmf('username')."='$username'";
}
$r=$empire->fetch1("select ".eReturnSelectMemberF('userid,username,email,groupid,userfen,userdate,registertime,phno,upic')." from ".eReturnMemberTable()." where ".$where."".do_dblimit_one());
if(empty($r['userid']))
{
	printerror("NotUsername","",1);
}
if(empty($username))
{
	$username=$r['username'];
}
$registertime=eReturnMemberRegtime($r['registertime'],'Y-m-d H:i:s');
$email=$r['email'];
$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='".$r['userid']."'".do_dblimit_one());
//取得表单
$formid=GetMemberFormId($r['groupid']);
$formr=$empire->fetch1("select filef,imgf,tobrf,viewenter from {$dbtbpre}enewsmemberform where fid='$formid'");
//导入模板
include(ECMS_PATH.'e/template/member/ShowInfo.php');
db_close();
$empire=null;
?>