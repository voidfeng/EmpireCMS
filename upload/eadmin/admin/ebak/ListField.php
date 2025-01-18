<?php
define('EmpireCMSAdmin','1');
define('InEmpireBak',TRUE);
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require("class/functions.php");
ehCheckCloseMods('ebak');
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
CheckLevel($logininid,$loginin,$classid,"dbdata");
//数据库
$mydbname=RepPostVar($_GET['mydbname']);
$mytbname=RepPostVar($_GET['mytbname']);
if(empty($mydbname)||empty($mytbname))
{
	printerror("NotChangeBakTable","history.go(-1)");
}
$form=RepPostVar($_GET['form']);
if(empty($form))
{
	$form='ebakchangetb';
}
eCheckStrType(4,$mydbname,1);
eCheckStrType(4,$mytbname,1);
eCheckStrType(5,$form,1);
$usql=$empire->usequery("use $mydbname");
$sql=do_dbFieldRowList($mytbname);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>表字段列表</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function ChangeAutoField(f)
{
	var tbname="<?=$mytbname?>";
	var chstr=tbname+"."+f;
	var r;
	var dh=",";
	var a;
	a=opener.document.<?=$form?>.autofield.value;
	r=a.split(chstr);
	if(r.length!=1)
	{return true;}
	if(a=="")
	{
		dh="";
	}
	opener.document.<?=$form?>.autofield.value+=dh+chstr;
	window.close();
}
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="32">位置：<b><?=$mydbname?>.<?=$mytbname?></b> 字段列表</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td height="27"> <div align="center">字段名</div></td>
    <td><div align="center">字段类型</div></td>
    <td><div align="center">字段属性</div></td>
    <td><div align="center">默认值</div></td>
    <td><div align="center">附加属性</div></td>
  </tr>
  <?php
  while($r=$empire->fetch($sql))
  {
	  $r['Field']="<a href='#ebak' onclick=\"ChangeAutoField('".$r['Field']."');\" title='加入去除自增值字段列表'>".$r['Field']."</a>";
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="27"> <div align="center">
        <?=$r['Field']?>
      </div></td>
    <td> <div align="center">
        <?=$r['Type']?>
      </div></td>
    <td> <div align="center">
        <?=$r['Key']?>
      </div></td>
    <td> <div align="center">
        <?=$r['Default']?>
      </div></td>
    <td> <div align="center">
        <?=$r['Extra']?>
      </div></td>
  </tr>
  <?php
  }
  db_close();
  $empire=null;
  ?>
</table>
<br>
</body>
</html>