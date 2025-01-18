<?php
if(!defined('empirecms'))
{
	exit();
}
if(!defined('EPAGECHECKPATH'))
{
	exit();
}

//目录
$check_pathck=str_replace('.','',$check_path);
$check_pathck=str_replace('/','',$check_pathck);
if($check_pathck<>'')
{
	exit();
}

if(EPAGECHECKPATH!=$check_path)
{
	exit();
}
$check_path=EPAGECHECKPATH;

//扣点
require_once(EPAGECHECKPATH."e/class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
$check_classid=(int)$check_classid;
$check_groupid=(int)$check_groupid;
$toreturnurl=eReturnSelfPage(0);	//返回页面地址
$gotourl=$ecms_config['member']['loginurl']?$ecms_config['member']['loginurl']:$public_r['newsurl']."e/member/login/";	//登陆地址
$loginuserid=(int)getcvar('mluserid');
$logingroupid=(int)getcvar('mlgroupid');
if(!$loginuserid)
{
	printerror2('本栏目需要会员级别以上才能查看','');
}
require_once(ECMS_PATH.'e/member/class/user.php');
require_once(ECMS_PATH.'e/data/dbcache/MemberLevel.php');
$link=db_connect();
$empire=new mysqlquery();
$mvguser=islogin();
$mvgresult=eMember_ReturnCheckMVGroup($mvguser,$check_groupid);
if($mvgresult<>'empire.cms')
{
	printerror2('您没有足够权限查看此栏目','');
}
?>