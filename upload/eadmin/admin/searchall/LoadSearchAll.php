<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
hCheckEcmsRHash();
//验证权限
CheckLevel($logininid,$loginin,$classid,"searchall");

@set_time_limit(0);

include("../../../e/data/dbcache/class.php");
include "../../../e/data/".LoadLang("pub/fun.php");
include('../../../e/class/schallfun.php');
//编码
$iconv='';
$char='';
$targetchar='';
if($ecms_config['sets']['pagechar']!='gb2312')
{
	$char=$ecms_config['sets']['pagechar']=='big5'?'BIG5':'UTF8';
	$targetchar='GB2312';
}
$lid=$_GET['lid'];
$start=$_GET['start'];
$addgethtmlpath="../";
LoadSearchAll($lid,$start,$logininid,$loginin);
db_close();
$empire=null;
?>