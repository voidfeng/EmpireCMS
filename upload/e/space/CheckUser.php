<?php
if(!defined('InEmpireCMS'))
{
	exit();
}

//统计访问
function UpdateSpaceViewStats($userid){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	if(!getcvar('dospacevstats'.$userid))
	{
		$sql=$empire->query("update {$dbtbpre}enewsmemberadd set viewstats=viewstats+1 where userid='".$userid."'".do_dblimit_upone());
		esetcookie("dospacevstats".$userid,1,time()+3600);
	}
}

//关闭
if($public_r['openspace']==1)
{
	printerror('CloseMemberSpace','',1);
}

require_once(ECMS_PATH.'e/space/spacefun.php');

//用户是否存在
$userid=intval($_GET['userid']);
if($userid)
{
	$add="userid='$userid'";
	$username='';
	$utfusername='';
	$uadd=egetmf('userid')."='$userid'";
}
else
{
	$username=RepPostVar($_GET['username']);
	if(empty($username))
	{
		printerror("NotUsername","",1);
	}
	$add="username='$username'";
	$utfusername=$username;
	$uadd=egetmf('username')."='$username'";
}
$ur=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".$uadd."".do_dblimit_one());
if(empty($ur['username']))
{
	printerror("NotUsername","",1);
}
if(empty($ur['checked']))
{
	printerror("NotUsername","",1);
}
//会员组
if($public_r['spacegids'])
{
	$mvgresult=eMember_ReturnCheckMVGroup($ur,$public_r['spacegids']);
	if($mvgresult<>'empire.cms')
	{
		printerror("UserNotSpace","",1);
	}
}
//实名验证
eCheckHaveTruename('msp',$ur['userid'],$ur['username'],$ur['isern'],$ur['checked'],0);

$userid=$userid?$userid:$ur['userid'];
$utfusername=$utfusername?$utfusername:$ur['username'];
$username=$username?$username:$ur['username'];
$groupid=(int)$ur['groupid'];
$userid=(int)$userid;
$utfusername=RepPostVar($utfusername);
$username=RepPostVar($username);
UpdateSpaceViewStats($userid);//统计访问
$addur=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='".$userid."'".do_dblimit_one());
//头像
//$userpic=$addur['userpic']?$addur['userpic']:$public_r['newsurl'].'e/data/images/nouserpic.gif';
$userpic=eMember_UpicReturnUrl($userid,$ur['upic']);
//空间地址
$spaceurl=eReturnDomainSiteUrl()."e/space/?userid=".$userid;
//空间名称
$addur['spacename']=stripSlashes($addur['spacename']);
$addur['spacegg']=stripSlashes($addur['spacegg']);
$spacename=$addur['spacename']?$addur['spacename']:$username." 的空间";
//空间模板
$spacestyleid=$addur['spacestyleid'];
if(empty($spacestyleid))
{
	$spacestyleid=$public_r['defspacestyleid'];
}
$spacestyleid=(int)$spacestyleid;
$spacestyler=$empire->fetch1("select stylepath from {$dbtbpre}enewsspacestyle where styleid='$spacestyleid'");
$spacestyle=$spacestyler['stylepath']?$spacestyler['stylepath']:'default';
?>