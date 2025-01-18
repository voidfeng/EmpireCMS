<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require("../../../e/member/class/user.php");
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
$r=array();
$enews=ehtmlspecialchars($_GET['enews']);
$payid=(int)$_GET['payid'];
eCheckStrType(4,$enews,1);
if($enews=='EditPayApi')
{
	$enews='EditPayApi';
}
else
{
	$enews='AddPayApi';
}
//formhash
$efh=heformhash_get($enews);

$url="在线支付&gt; <a href=PayApi.php".$ecms_hashur['whehref'].">管理支付接口</a>&nbsp;>&nbsp;增加支付接口";
if($enews=='EditPayApi')
{
	$r=$empire->fetch1("select * from {$dbtbpre}enewspayapi where payid='$payid'");
	$url="在线支付&gt; <a href=PayApi.php".$ecms_hashur['whehref'].">管理支付接口</a>&nbsp;>&nbsp;配置支付接口：<b>".$r['paytype']."</b>";
}
$registerpay='';
//pay
if($r['paytype']=='')
{
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>支付接口</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit5" value="管理支付记录" onclick="self.location.href='ListPayRecord.php<?=$ecms_hashur['whehref']?>';">
        &nbsp;&nbsp; 
        <input type="button" name="Submit52" value="支付参数设置" onclick="self.location.href='SetPayFen.php<?=$ecms_hashur['whehref']?>';">
      </div></td>
  </tr>
</table>
<form name="setpayform" method="post" action="PayApi.php" enctype="multipart/form-data" autocomplete="off">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2">配置支付接口 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="payid" type="hidden" id="payid" value="<?=$payid?>">      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">接口类型：</div></td>
      <td height="25"> 
        <?=$r['paytype']?>      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">接口状态：</div></td>
      <td height="25"><input type="radio" name="isclose" value="0"<?=$r['isclose']==0?' checked':''?>>
        开启 
        <input type="radio" name="isclose" value="1"<?=$r['isclose']==1?' checked':''?>>
        关闭</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">接口目录：</div></td>
      <td height="25">/e/payapi/
        <input name="paytype" type="text" id="paytype" value="<?=$r['paytype']?>" size="29"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25"><div align="right">接口名称：</div></td>
      <td width="81%" height="25"><input name="payname" type="text" id="payname" value="<?=$r['payname']?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">接口图标地址：</div></td>
      <td height="25"><input name="paylogo" type="text" id="paylogo" value="<?=$r['paylogo']?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top"><div align="right">接口描述：</div></td>
      <td height="25"><textarea name="paysay" cols="65" rows="6" id="paysay"><?=ehtmlspecialchars($r['paysay'])?></textarea></td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">显示排序：</div></td>
      <td height="25"><input name=myorder type=text id="myorder" value='<?=$r['myorder']?>' size="35">
        <font color="#666666">(值越小显示越前面)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">异步支付方式：</div></td>
      <td height="25"><input type="radio" name="opennturl" value="1"<?=$r['opennturl']==1?' checked':''?>>
        开启
          <input type="radio" name="opennturl" value="0"<?=$r['opennturl']==0?' checked':''?>> 
          关闭
      <font color="#666666">(payend_nt.php页面)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">&nbsp;</td>
      <td height="25">[<a href="#ecms" onclick="document.getElementById('moresetid').style.display='';">显示详细配置选项</a>]</td>
    </tr>
    
	<tbody id="moresetid" style="display:none">
	<tr bgcolor="#FFFFFF">
      <td height="25">&nbsp;</td>
      <td height="25"><font color="#666666">说明：由于现在支付接口多数用可解密的方式签名，所以不建议敏感信息用在线设置。</font></td>
    </tr>
    <?php
	if(stristr($r['paytype'],'alipay'))
	{
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">支付宝类型：</div></td>
      <td height="25"><select name="paymethod" id="paymethod">
          <option value="0"<?=$r['paymethod']==0?' selected':''?>>使用标准双接口</option>
          <option value="1"<?=$r['paymethod']==1?' selected':''?>>使用即时到帐交易接口</option>
          <option value="2"<?=$r['paymethod']==2?' selected':''?>>使用担保交易接口</option>
        </select></td>
    </tr>
    <?php
	}
	?>
	<?php
	if(stristr($r['paytype'],'alipay'))
	{
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">支付宝帐号：</div></td>
      <td height="25"><input name="payemail" type="text" id="payemail" value="<?=$r['payemail']?>" size="35"></td>
    </tr>
    <?php
	}
	?>
	<tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">公众账号ID：</div></td>
      <td height="25"><input name="payappid" type="text" id="payappid" value="<?=$r['payappid']?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right"><?=stristr($r['paytype'],'alipay')?'合作者身份(parterID)':'商户号(ID)'?>：</div></td>
      <td height="25"><input name="payuser" type="text" id="payuser" value="<?=$r['payuser']?>" size="35"> 
        <?=$registerpay?>      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">Openid标识：</div></td>
      <td height="25"><input name="payopenid" type="text" id="payopenid" value="<?=$r['payopenid']?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">商户附加信息</div></td>
      <td height="25"><input name="paymchid" type="text" id="paymchid" value="<?=$r['paymchid']?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right"><?=stristr($r['paytype'],'alipay')?'交易安全校验码(key)':'密钥(KEY)'?>：</div></td>
      <td height="25"><input name="paykey" type="text" id="paykey" value="<?=$r['paykey']?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="right">手续费：</div></td>
      <td height="25"><input name=payfee type=text id="payfee" value='<?=$r['payfee']?>' size="35">
        % </td>
    </tr>
    
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">自定义项1：</div></td>
      <td height="25"><input name=diyset1 type=text id="diyset1" value='<?=$r['diyset1']?>' size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">自定义项2：</div></td>
      <td height="25"><input name=diyset2 type=text id="diyset2" value='<?=$r['diyset2']?>' size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">自定义项3：</div></td>
      <td height="25"><input name=diyset3 type=text id="diyset3" value='<?=$r['diyset3']?>' size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">自定义项4：</div></td>
      <td height="25"><input name=diyset4 type=text id="diyset4" value='<?=$r['diyset4']?>' size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="right">自定义项5：</div></td>
      <td height="25"><input name=diyset5 type=text id="diyset5" value='<?=$r['diyset5']?>' size="35"></td>
    </tr>
	</tbody>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value=" 设 置 "> &nbsp;&nbsp;&nbsp; 
        <input type="reset" name="Submit2" value="重置"></td>
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