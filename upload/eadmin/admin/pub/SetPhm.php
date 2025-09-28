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

//设置手机短信参数
function eSetPhm($add,$userid,$username){
	global $empire,$dbtbpre;
	CheckLevel($userid,$username,$classid,"public");//验证权限
	$phmmax=(int)$add['phmmax'];
	$phmdaymax=(int)$add['phmdaymax'];
	$phmonemax=(int)$add['phmonemax'];
	$phmretime=(int)$add['phmretime'];
	$phmlen=(int)$add['phmlen'];
	$phmouttime=(int)$add['phmouttime'];
	$phmtog=(int)$add['phmtog'];
	$phmopen=(int)$add['phmopen'];
	$phmern=(int)$add['phmern'];
	$phmmust=(int)$add['phmmust'];
	$phmreg=(int)$add['phmreg'];
	$phmckst=(int)$add['phmckst'];
	$phmlgtoreg=(int)$add['phmlgtoreg'];
	$phmckrnd=hRepPostStr($add['phmckrnd'],1,1);
	//允许的操作
	$phmdo='';
	$phmdock=$add['phmdock'];
	$phmdock=eCheckEmptyArray($phmdock);
	$phmdocount=count($phmdock);
	if($phmdocount)
	{
		$phmdo=',';
		for($pdi=0;$pdi<$phmdocount;$pdi++)
		{
			$phmdo.=$phmdock[$pdi].',';
		}
	}
	$phmdo=hRepPostStr($phmdo,1,1);
	
	$sql=$empire->query("update {$dbtbpre}enewspublicadd set phmmax='$phmmax',phmdaymax='$phmdaymax',phmonemax='$phmonemax',phmretime='$phmretime',phmlen='$phmlen',phmouttime='$phmouttime',phmtog='$phmtog',phmopen='$phmopen',phmern='$phmern',phmmust='$phmmust',phmreg='$phmreg',phmdo='$phmdo',phmckst='$phmckst',phmckrnd='$phmckrnd',phmlgtoreg='$phmlgtoreg'".do_dblimit_upone());
	if($sql)
	{
		GetConfig();
		//操作日志
		insert_dolog("");
		printerror("SetPhmSuccess","SetPhm.php".hReturnEcmsHashStrHref2(1));
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
if($enews=="SetPhm")//设置参数
{
	eSetPhm($_POST,$logininid,$loginin);
}
else
{}

$r=$empire->fetch1("select * from {$dbtbpre}enewspublicadd".do_dblimit_one());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>手机短信设置</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32"><p>位置：手机短信设置</p>
    </td>
  </tr>
</table>
<form name="setpublic" method="post" action="SetPhm.php" onSubmit="return confirm('确认设置?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('SetPhm'); ?>
    <tr class="header"> 
      <td height="25" colspan="2">手机短信设置 
        <input name="enews" type="hidden" value="SetPhm"></td>
    </tr>
    
    <tr bgcolor="#FFFFFF">
      <td width="19%" height="25">开启短信接口：</td>
      <td width="81%"><input name="phmopen" type="checkbox" id="phmopen" value="1"<?=$r['phmopen']==1?' checked':''?>>
        开启</td>
    </tr>
	
	<tr bgcolor="#FFFFFF">
	  <td height="25">关闭的模块：</td>
	  <td>
	  <input name="phmdock[]" type="checkbox" id="phmdock[]" value="login"<?=strstr($r['phmdo'],',login,')?' checked':''?>>
	  登录
      <input name="phmdock[]" type="checkbox" id="phmdock[]" value="reg"<?=strstr($r['phmdo'],',reg,')?' checked':''?>>
	  注册
      <input name="phmdock[]" type="checkbox" id="phmdock[]" value="bind"<?=strstr($r['phmdo'],',bind,')?' checked':''?>>
	  绑定手机
	  <input name="phmdock[]" type="checkbox" id="phmdock[]" value="delb"<?=strstr($r['phmdo'],',delb,')?' checked':''?>>
	  取消绑定手机</td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25">手机注册会员：</td>
      <td><select name="phmreg" id="phmreg">
        <option value="0"<?=$r['phmreg']==0?' selected':''?>>关闭</option>
        <option value="1"<?=$r['phmreg']==1?' selected':''?>>需填写用户名和密码</option>
        <option value="2"<?=$r['phmreg']==2?' selected':''?>>只需填写手机号</option>
      </select></td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25">登录时自动注册会员：</td>
      <td><input name="phmlgtoreg" type="checkbox" id="phmlgtoreg" value="1"<?=$r['phmlgtoreg']==1?' checked':''?>>
        启用 <font color="#666666">(登录时，如果手机号不存在，则自动注册会员)</font></td>
    </tr>

    <tr bgcolor="#FFFFFF">
      <td height="25">验证码字符数：</td>
      <td><input name="phmlen" type="text" id="phmlen" value="<?=$r['phmlen']?>" size="38">
        位</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">验证码字符组成：</td>
      <td><select name="phmtog" id="phmtog">
        <option value="0"<?=$r['phmtog']==0?' selected':''?>>数字</option>
        <option value="1"<?=$r['phmtog']==1?' selected':''?>>字母</option>
        <option value="2"<?=$r['phmtog']==2?' selected':''?>>数字+字母</option>
      </select></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">验证码超时时间：</td>
      <td><input name="phmouttime" type="text" id="phmouttime" value="<?=$r['phmouttime']?>" size="38">
        秒</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">每天最大发送数：</td>
      <td><input name="phmdaymax" type="text" id="phmdaymax" value="<?=$r['phmdaymax']?>" size="38">
        条短信<font color="#666666">(0为不限)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">单人每天发送数：</td>
      <td><input name="phmonemax" type="text" id="phmonemax" value="<?=$r['phmonemax']?>" size="38">
条短信<font color="#666666">(0为不限)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">短信发送间隔时间：</td>
      <td><input name="phmretime" type="text" id="phmretime" value="<?=$r['phmretime']?>" size="38">
秒<font color="#666666">(0为不限)</font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25">表单超时时间：</td>
      <td><input name="phmckst" type="text" id="phmckst" value="<?=$r['phmckst']?>" size="38">
        秒<font color="#666666">(0为不启用，从页面打开到发送短信时间间隔)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">表单超时验证随机码：</td>
      <td>
	  <input name="phmckrnd" type="text" id="phmckrnd" value="<?=$r['phmckrnd']?>" size="38">
	    <font color="#666666">
	    <input type="button" name="Submit3222" value="随机" onClick="document.setpublic.phmckrnd.value='<?=make_password(46)?>';">
      (填写10~50个任意字符，最好多种字符组合)</font>
	  </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">绑定手机后自动实名：</td>
      <td><input name="phmern" type="checkbox" id="phmern" value="1"<?=$r['phmern']==1?' checked':''?>>
        是 <font color="#666666">(选择后将不能取消手机绑定，只能更改绑定)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">强制绑定手机：</td>
      <td><input name="phmmust" type="checkbox" id="phmmust" value="1"<?=$r['phmmust']==1?' checked':''?>>
        是 <font color="#666666">(设置后会员登录会转向绑定手机页面) </font></td>
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