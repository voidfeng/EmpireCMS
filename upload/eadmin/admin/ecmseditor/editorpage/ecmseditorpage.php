<?php
define('EmpireCMSAdmin','1');
require('../../../../e/class/connect.php');
require('../../../../e/class/functions.php');
$link=db_connect();
$empire=new mysqlquery();
$editor=2;
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

//接收参数
function EcmsEditor_PageGetVar($add){
	$r['showmod']=(int)$add['showmod'];
	$r['type']=(int)$add['type'];
	$r['classid']=(int)$add['classid'];
	$r['filepass']=(int)$add['filepass'];
	$r['infoid']=(int)$add['infoid'];
	$r['modtype']=(int)$add['modtype'];
	$r['sinfo']=(int)$add['sinfo'];
	$r['doing']=ehtmlspecialchars($add['doing']);
	$r['fileno']=ehtmlspecialchars($add['fileno']);
	$r['InstanceName']=ehtmlspecialchars($add['InstanceName']);
	$r['InstanceId']=intval(str_replace('cke_','',$add['InstanceId']));
	return $r;
}

$ecms_topager=array();
$doecmspage=ehtmlspecialchars($_GET['doecmspage']);
$ecms_topager=EcmsEditor_PageGetVar($_GET);

//分类
$fcclassid=(int)$_GET['classid'];
$fcwhere='';
if($fcclassid)
{
	include('../../../../e/data/dbcache/class.php');
	$fcclasswhere=ReturnClass($class_r[$fcclassid]['featherclass']);
	$fcwhere.="classid=0 or classid='$fcclassid' or (".$fcclasswhere.")";
}
$addcidselects=PubReturnSelectClass('enewsfileclass','cid','cname','',0,'myorder asc',$fcwhere);
$addcidselects2=PubReturnSelectClass('enewsfileclasst','cid','cname','',0,'myorder asc',$fcwhere);

$pagefile='';
if($doecmspage=='TranImg')//上传图片
{
	$pagefile='TranImg.php';
}
elseif($doecmspage=='TranFile')//上传附件
{
	$pagefile='TranFile.php';
}
elseif($doecmspage=='TranFlash')//上传FLASH
{
	$pagefile='TranFlash.php';
}
elseif($doecmspage=='TranMedia')//上传视频
{
	$pagefile='TranMedia.php';
}
elseif($doecmspage=='TranMore')//上传多图
{
	$pagefile='TranMore.php';
}
else
{
	exit();
}
@include($pagefile);

db_close();
$empire=null;
?>