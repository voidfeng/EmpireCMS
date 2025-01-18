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

$picurl=ehtmlspecialchars($_GET['picurl']);
$pic_width=ehtmlspecialchars($_GET['pic_width']);
$pic_height=ehtmlspecialchars($_GET['pic_height']);
$url=ehtmlspecialchars($_GET['url']);
eCheckStrType(12,$picurl,1);
eCheckStrType(12,$pic_width,1);
eCheckStrType(12,$pic_height,1);
eCheckStrType(12,$url,1);
?>
<title>广告预览</title>
<a href="<?=$url?>" target=_blank><img src="<?=$picurl?>" border=0 width="<?=$pic_width?>" height="<?=$pic_height?>"></a>
