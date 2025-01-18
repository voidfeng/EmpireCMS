<?php
define('EmpireCMSAdmin','1');
define('EmpireCMSEARPage','1');
require("../../e/class/connect.php");
require("../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

//addrnd
$rndsr=array();
DoECreatAddAuthRnd($logininid,$loginin,$loginrnd,$loginlevel,$rndsr,0);

db_close();
$empire=null;

$ecmstourl='admin.php'.$ecms_hashur['whehref'];
echo'<meta http-equiv="refresh" content="0;url='.$ecmstourl.'">';
?>