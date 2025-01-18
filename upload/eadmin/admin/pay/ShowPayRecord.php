<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require("../../../e/class/com_functions.php");
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
CheckLevel($logininid,$loginin,$classid,"pay");
$id=(int)$_GET['id'];
$st=(int)$_GET['st'];
if($st==1)
{
	$tb='enewspayrecordst';
}
else
{
	$tb='enewspayrecord';
}
$r=$empire->fetch1("select * from ".$dbtbpre.$tb." where id='$id'".do_dblimit_one());
if(!$r['id'])
{
	printerror('ErrorUrl','');
}
//支付者
if($r['userid'])
{
	$username="<a href='../member/AddMember.php?enews=EditMember&userid=".$r['userid'].$ecms_hashur['ehref']."' target=_blank>".$r['username']."</a>";
}
else
{
	$username="游客(".$r['username'].")";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>查看支付记录</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class=tableborder style="WORD-BREAK: break-all; WORD-WRAP: break-word">
  <tr class=header> 
    <td height="25" colspan="2">
	<?php
	if($st==1)
	{
	?>
		查看待支付记录
	<?php
	}
	else
	{
	?>
		查看支付成功记录
	<?php
	}
	?>	</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">访问端：</td>
    <td height="25"><?=eReturnMPname($r['mpid'])?></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td width="20%" height="25">编号：</td>
    <td width="80%" height="25"> 
      <?=$r['id']?>    </td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">支付接口：</td>
    <td height="25"><strong><?=$r['paytype']?></strong></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">网站订单号：</td>
    <td height="25"><strong><?=$r['payddno']?></strong></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">支付订单号：</td>
    <td height="25"><strong>
	<?php
	if($st==1)
	{
		echo"待支付";
	}
	else
	{
		echo $r['orderid'];
	}
	?>
	</strong></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">支付者：</td>
    <td height="25"><strong><?=$username?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#666666">(支付IP：<?=$r['payip']?>)</font></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">金额：</td>
    <td height="25"><strong><?=$r['money']?></strong></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">提交时间：</td>
    <td height="25"><?=$r['posttime']?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">支付时间：</td>
    <td height="25">
	<?php
	if($st==1)
	{
		echo"待支付";
	}
	else
	{
		echo $r['endtime'];
	}
	?>	</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">操作事件：</td>
    <td height="25"><?=$r['paydo']?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">操作内容：</td>
    <td height="25"><?=$r['payfor']?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">商品名：</td>
    <td height="25"><?=$r['pname']?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">商品描述：</td>
    <td height="25"><?=$r['psay']?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25">备注：</td>
    <td height="25"><?=$r['paybz']?></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="2"><div align="center">[ <a href="javascript:window.close();">关 
        闭</a> ]</div></td>
  </tr>
</table>
</body>
</html>
<?php
db_close();
$empire=null;
?>