<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require("../../../e/data/dbcache/class.php");
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

$js=ehtmlspecialchars($_GET['js']);
$classid=ehtmlspecialchars($_GET['classid']);
$ztid=ehtmlspecialchars($_GET['ztid']);
$p=ehtmlspecialchars($_GET['p']);
if($classid)
{
	$url=$js;
}
else
{
	$url="../../../d/js/".$p."/".$js.".js";
}
//checkurl
eCheckOtherViewUrl($url,0,1);

?>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script src="<?=$url?>"></script>
