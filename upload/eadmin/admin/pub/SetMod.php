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
//验证权限
CheckLevel($logininid,$loginin,$classid,"public");

//设置系统模型参数
function eSetMod($add,$userid,$username){
	global $empire,$dbtbpre;
	CheckLevel($userid,$username,$classid,"public");//验证权限
	$qedtext=(int)$add['qedtext'];
	$qedmpids=eReturnSetGroups($add['qedmpid']);
	$qedmpids=hRepPostStr($qedmpids,1);
	$qedmids=eReturnSetGroups($add['qedmid']);
	$qedmids=hRepPostStr($qedmids,1);
	$qedtemp=AddAddsData($add['qedtemp']);
	
	$sql=$empire->query("update {$dbtbpre}enewspublicadd set qedtext='$qedtext',qedmpids='$qedmpids',qedmids='$qedmids',qedtemp='$qedtemp'".do_dblimit_upone());
	if($sql)
	{
		GetConfig();
		//操作日志
		insert_dolog("");
		printerror("SetModSuccess","SetMod.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}


$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="SetMod")//设置参数
{
	eSetMod($_POST,$logininid,$loginin);
}
else
{}

$r=$empire->fetch1("select * from {$dbtbpre}enewspublicadd".do_dblimit_one());

//访问端
$mpids='';
$i=0;
$mpsql=$empire->query("select pid,pname from {$dbtbpre}enewsmoreport order by pid");
while($mpr=$empire->fetch($mpsql))
{
	$i++;
	if($i%4==0)
	{
		$br="<br>";
	}
	else
	{
		$br="";
	}
	$select='';
	if(strstr($r['qedmpids'],','.$mpr['pid'].','))
	{
		$select=' checked';
	}
	$mpids.="<input type=checkbox name=qedmpid[] value='".$mpr['pid']."'".$select.">".$mpr['pname']."&nbsp;&nbsp;".$br;
}

//系统模型
$mids='';
$i=0;
$modsql=$empire->query("select mid,mname from {$dbtbpre}enewsmod order by myorder,mid");
while($modr=$empire->fetch($modsql))
{
	$i++;
	if($i%4==0)
	{
		$br="<br>";
	}
	else
	{
		$br="";
	}
	$select='';
	if(strstr($r['qedmids'],','.$modr['mid'].','))
	{
		$select=' checked';
	}
	$mids.="<input type=checkbox name=qedmid[] value='".$modr['mid']."'".$select.">".$modr['mname']."&nbsp;&nbsp;".$br;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>系统模型参数设置</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32"><p>位置：系统模型参数设置</p>
    </td>
  </tr>
</table>
<form name="setpublic" method="post" action="SetMod.php" onsubmit="return confirm('确认设置?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('SetMod'); ?>
    <tr class="header"> 
      <td height="25" colspan="2">系统模型参数设置 
        <input name="enews" type="hidden" value="SetMod"></td>
    </tr>
    <tr>
      <td height="25" colspan="2"><strong>前台编辑器投稿采用文本框</strong></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width="19%" height="25">全部采用文本框：</td>
      <td width="81%"><input name="qedtext" type="checkbox" id="qedtext" value="1"<?=$r['qedtext']==1?' checked':''?>>
        是，全部</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">限访问端采用文本框：</td>
      <td><?=$mpids?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">限系统模型采用文本框：</td>
      <td><?=$mids?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" valign="top"><p>文本框显示模板：<br>
          <font color="#666666">(
          <br>
          字段值变量:[!--ec.fi.value--]
      <br>
      字段名变量:[!--ec.fi.name--]<br>
      字段设置高度:[!--ec.fi.height--]<br>
      字段设置宽度:[!--ec.fi.width--]
      <br>
      系统模型ID:[!--ec.fi.mid--]<br>
      访问端ID:[!--ec.fi.mpid--]<br>
      )</font></p>
      </td>
      <td><textarea name="qedtemp" cols="65" rows="14" id="qedtemp" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r['qedtemp']))?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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