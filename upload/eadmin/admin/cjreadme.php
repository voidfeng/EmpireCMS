<?php
define('EmpireCMSAdmin','1');
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
//验证权限
CheckLevel($logininid,$loginin,$classid,"cj");


//开启采集
function CjReadMeDoOpen($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"cj");
	$egop=(int)$add['egop'];
	$r=$empire->fetch1("select canusecj from {$dbtbpre}enewspublicadd".do_dblimit_one());
	if($r['canusecj'])
	{
	}
	else
	{
		$sql=$empire->query("update {$dbtbpre}enewspublicadd set canusecj=1;");
	}
	$gotourl="ListInfoClass.php".hReturnEcmsHashStrHref2(1);
	echo'<meta http-equiv="refresh" content="0;url='.$gotourl.'">';
	exit();
}


$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//开启采集
if($enews=="CjReadMeOpen")
{
	$add=$_POST;
	CjReadMeDoOpen($add,$logininid,$loginin);
}

$egop=(int)$_GET['egop'];

//formhash
$efh=heformhash_get('CjReadMeOpen',1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>确认开启采集功能</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：采集&nbsp;&gt;&nbsp;确认开启采集功能</td>
  </tr>
</table>

<form name="form1" method="post" action="cjreadme.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25"><div align="center">帝国CMS用户采集功能使用许可协议</div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"><div align="center">
        <label>
        <textarea name="textarea" cols="100" rows="16"></textarea>
        </label>
      </div></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF"><div align="center">
        <label>
        <input type="button" name="Submit2" value="不同意" onclick="history.go(-1);">
		&nbsp;&nbsp;
        <input type="button" name="Submit" value="同意并开启" onclick="if(confirm('确认要开启采集功能?')){self.location.href='cjreadme.php?enews=CjReadMeOpen&egop=<?=$egop?><?=$ecms_hashur['href'].$efh?>';}">
        </label>
      </div></td>
    </tr>
  </table>
</form>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>