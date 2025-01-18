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

//参数
$ecmsck=RepPostVar($_GET['ecmsck']);
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
//参数

$ecmscklist=',eaShowInfo,eaShowInfoUrl,eaGoUrlFlink,eaGoUrlUserlist,eaGoUrlUserpage,eaGoUrlClass,eaGoUrlZt,eaGoUrlInfotype,eaGoUrlTags,eaGoUrlCjpage,eaGoUrlSearchUrl,';

//验证
$ecmsck=str_replace(',','',$ecmsck);
if(!$classid||!$ecmsck)
{
	printerror('ErrorUrl','');
}
if(!strstr($ecmscklist,','.$ecmsck.','))
{
	printerror('ErrorUrl','');
}
$sitedmurl=eReturnDmUrl();

$r=eapage_SetPassShowInfo($classid,$id,$ecmsck);
$gotourl=$sitedmurl.'e/eapage/eaGotoPage.php?'.$r['urlcs'];

db_close();
$empire=null;

if($gotourl)
{
	echo'<meta http-equiv="refresh" content="0;url='.$gotourl.'">';
}

?>