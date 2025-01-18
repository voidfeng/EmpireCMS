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
CheckLevel($logininid,$loginin,$classid,"setsafe");
if($ecms_config['esafe']['openonlinesetting']==0||$ecms_config['esafe']['openonlinesetting']==1)
{
	echo"没有开启后台在线配置参数，如果要使用在线配置先修改/e/config/config.php文件的\$ecms_config['esafe']['openonlinesetting']变量设置开启";
	exit();
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include('setfun.php');
}
if($enews=='SetSafe')
{
	SetSafe($_POST,$logininid,$loginin);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>安全参数配置</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="32">位置：<a href="SetSafe.php<?=$ecms_hashur['whehref']?>">安全参数配置</a> 
      <div align="right"> </div></td>
  </tr>
</table>
<form name="setform" method="post" action="SetSafe.php" onSubmit="return confirm('确认设置?');" autocomplete="off">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('SetSafe'); ?>
    <tr class="header"> 
      <td height="25" colspan="2">安全参数配置 
        <input name="enews" type="hidden" id="enews" value="SetSafe"> </td>
    </tr>
    <tr> 
      <td height="25" colspan="2">后台安全相关配置</td>
    </tr>
    <tr> 
      <td width="17%" height="25" bgcolor="#FFFFFF"> <div align="left">后台登录认证码</div></td>
      <td width="83%" height="25" bgcolor="#FFFFFF"> <input name="loginauth" type="password" id="loginauth" value="" size="35"> 
<?php
if($ecms_config['esafe']['loginauth'])
{
	echo'<font color="#FF0000">[已设置]</font>&nbsp;&nbsp;';
}
?>
		<font color="#666666">(如果设置登录需要输入此认证码才能通过，不修改请留空，如果不设置填“null”)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">后台登录地址验证参数变量名</td>
      <td height="25" bgcolor="#FFFFFF"><input name="eloginurlcsvar" type="text" id="eloginurlcsvar" value="<?=$ecms_config['esafe']['eloginurlcsvar']?>" size="35">
秒 <font color="#666666">(由字母和数字组成，需以字母为开头。空为id)</font></td>
    </tr>
	<tr>
      <td height="25" bgcolor="#FFFFFF">后台登录地址验证参数</td>
      <td height="25" bgcolor="#FFFFFF"><input name="eloginurlcs" type="text" id="eloginurlcs" value="<?=$ecms_config['esafe']['eloginurlcs']?>" size="35">
秒 <font color="#666666">(可填写除“&lt;、&gt;、'、"、&”以外的字符，空为不开启；设置后，后台访问地址后面要加“?参数变量名=该参数”)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="left">后台登录COOKIE认证码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name="ecookiernd" type="text" id="ecookiernd" value="<?=$ecms_config['esafe']['ecookiernd']?>" size="35"> 
        <input type="button" name="Submit3" value="随机" onClick="document.setform.ecookiernd.value='<?=make_password(36)?>';"> 
        <font color="#666666">(填写10~50个任意字符，最好多种字符组合)</font></td>
    </tr>
	<tr> 
      <td height="25" bgcolor="#FFFFFF">后台开启一次性密码登录</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="loginonepass" value="1"<?=$ecms_config['esafe']['loginonepass']==1?' checked':''?>>
        开启 
        <input type="radio" name="loginonepass" value="0"<?=$ecms_config['esafe']['loginonepass']==0?' checked':''?>>
        关闭
      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">后台开启验证登录IP</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="ckhloginip" value="1"<?=$ecms_config['esafe']['ckhloginip']==1?' checked':''?>>
        开启 
        <input type="radio" name="ckhloginip" value="0"<?=$ecms_config['esafe']['ckhloginip']==0?' checked':''?>>
        关闭 <font color="#666666">(如果上网的IP是变动的，不要开启)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">后台启用SESSION验证</td>
      <td height="25" bgcolor="#FFFFFF"><input type="radio" name="ckhsession" value="1"<?=$ecms_config['esafe']['ckhsession']==1?' checked':''?>>
        开启 
        <input type="radio" name="ckhsession" value="0"<?=$ecms_config['esafe']['ckhsession']==0?' checked':''?>>
        关闭 <font color="#666666">(更安全，需空间支持SESSION)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">记录登录日志</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="theloginlog" value="0"<?=$ecms_config['esafe']['theloginlog']==0?' checked':''?>>
        开启 
        <input type="radio" name="theloginlog" value="1"<?=$ecms_config['esafe']['theloginlog']==1?' checked':''?>>
        关闭</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">记录操作日志</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="thedolog" value="0"<?=$ecms_config['esafe']['thedolog']==0?' checked':''?>>
        开启 
        <input type="radio" name="thedolog" value="1"<?=$ecms_config['esafe']['thedolog']==1?' checked':''?>>
        关闭</td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">开启访问来源验证</td>
      <td height="25" bgcolor="#FFFFFF"><select name="ckfromurl" id="ckfromurl">
          <option value="0"<?=$ecms_config['esafe']['ckfromurl']==0?' selected':''?>>不开启验证</option>
          <option value="1"<?=$ecms_config['esafe']['ckfromurl']==1?' selected':''?>>开启前后台验证</option>
          <option value="2"<?=$ecms_config['esafe']['ckfromurl']==2?' selected':''?>>仅开启后台验证</option>
          <option value="3"<?=$ecms_config['esafe']['ckfromurl']==3?' selected':''?>>仅开启前台验证</option>
		  <option value="4"<?=$ecms_config['esafe']['ckfromurl']==4?' selected':''?>>开启前后台验证(严格)</option>
		  <option value="5"<?=$ecms_config['esafe']['ckfromurl']==5?' selected':''?>>仅开启后台验证(严格)</option>
		  <option value="6"<?=$ecms_config['esafe']['ckfromurl']==6?' selected':''?>>仅开启前台验证(严格)</option>
        </select>
        <font color="#666666">(设置禁止非本站访问地址来源)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">开启后台来源认证码</td>
      <td height="25" bgcolor="#FFFFFF"><select name="ckhash" id="ckhash">
        <option value="0"<?=$ecms_config['esafe']['ckhash']==0?' selected':''?>>金刚模式</option>
        <option value="1"<?=$ecms_config['esafe']['ckhash']==1?' selected':''?>>刺猬模式</option>
        <option value="2"<?=$ecms_config['esafe']['ckhash']==2?' selected':''?>>关闭验证</option>
      </select>
        <font color="#666666">(推荐启用“金刚模式”，对外部访问与提交进行防御)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF">访问变量名：
        <input name="ckhashename" type="text" id="ckhashename" value="<?=$ecms_config['esafe']['ckhashename']?>" size="12">
      ，提交变量名：
      <input name="ckhashrname" type="text" id="ckhashrname" value="<?=$ecms_config['esafe']['ckhashrname']?>" size="12">
      <font color="#666666">(必须字母开头,并且只能由字母、数字、下划线组成)</font></td>
    </tr>
	<tr>
      <td height="25" bgcolor="#FFFFFF">后台表单独立来源认证码</td>
      <td height="25" bgcolor="#FFFFFF"><select name="ckformhash" id="ckformhash">
        <option value="0"<?=$ecms_config['esafe']['ckformhash']==0?' selected':''?>>开启</option>
        <option value="1"<?=$ecms_config['esafe']['ckformhash']==1?' selected':''?>>关闭</option>
      </select>
        <font color="#666666">(对外部提交进行防御，开启后每个表单都采用独立认证码)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">后台访问的UserAgent包含</td>
      <td height="25" bgcolor="#FFFFFF"><input name="ckhuseragent" type="text" id="ckhuseragent" value="<?=$ecms_config['esafe']['ckhuseragent']?>" size="35" autocomplete="off">
        <font color="#666666">(区分大小写，多个用“||”半角双竖线隔开，设置后UserAgent信息必须包含这些字符才能访问后台)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">后台数据加密组合字符串</td>
      <td height="25" bgcolor="#FFFFFF"><input name="hendatakey" type="text" id="hendatakey" value="<?=$ecms_config['esafe']['hendatakey']?>" size="35">
        <input type="button" name="Submit323" value="随机" onClick="document.setform.hendatakey.value='<?=make_password(52)?>';">
      <font color="#666666">(填写30~70个任意字符)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">前台数据加密组合字符串</td>
      <td height="25" bgcolor="#FFFFFF"><input name="endatakey" type="text" id="endatakey" value="<?=$ecms_config['esafe']['endatakey']?>" size="35">
        <input type="button" name="Submit324" value="随机" onClick="document.setform.endatakey.value='<?=make_password(52)?>';">
      <font color="#666666">(填写30~70个任意字符)</font></td>
    </tr>
	<tr> 
      <td width="17%" height="25" bgcolor="#FFFFFF"> <div align="left">整站访问密码</div></td>
      <td width="83%" height="25" bgcolor="#FFFFFF"> <input name="viewpass" type="password" id="viewpass" size="35"> 
<?php
if($ecms_config['esafe']['viewpass'])
{
	echo'<font color="#FF0000">[已设置]</font>&nbsp;&nbsp;';
}
?>
		<font color="#666666">(如果设置访问所有动态页面需要输入此密码才能访问，不修改请留空，如果不设置填“null”)</font></td>
    </tr>
	 <tr> 
      <td width="17%" height="25" bgcolor="#FFFFFF"> <div align="left">整站访问密码验证变量名</div></td>
      <td width="83%" height="25" bgcolor="#FFFFFF"> <input name="viewpassvar" type="text" id="viewpassvar" value="<?=$ecms_config['esafe']['viewpassvar']?>" size="35"> 
		<font color="#666666">(由字母和数字组成，需以字母为开头)</font></td>
    </tr>
	<tr> 
      <td width="17%" height="25" bgcolor="#FFFFFF"> <div align="left">后台访问密码</div></td>
      <td width="83%" height="25" bgcolor="#FFFFFF"> <input name="hviewpass" type="password" id="hviewpass" value="" size="35"> 
<?php
if($ecms_config['esafe']['hviewpass'])
{
	echo'<font color="#FF0000">[已设置]</font>&nbsp;&nbsp;';
}
?>
		<font color="#666666">(如果设置访问后台动态页面需要输入此密码才能访问，不修改请留空，如果不设置填“null”)</font></td>
    </tr>
	 <tr> 
      <td width="17%" height="25" bgcolor="#FFFFFF"> <div align="left">后台访问密码验证变量名</div></td>
      <td width="83%" height="25" bgcolor="#FFFFFF"> <input name="hviewpassvar" type="text" id="hviewpassvar" value="<?=$ecms_config['esafe']['hviewpassvar']?>" size="35"> 
		<font color="#666666">(由字母和数字组成，需以字母为开头)</font></td>
    </tr>
    <tr> 
      <td height="25" colspan="2">COOKIE配置</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">COOKIE作用域</td>
      <td height="25" bgcolor="#FFFFFF"> <input name="cookiedomain" type="text" id="cookiedomain" value="<?=$ecms_config['cks']['ckdomain']?>" size="35">      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">COOKIE作用路径</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookiepath" type="text" id="cookiepath" value="<?=$ecms_config['cks']['ckpath']?>" size="35"></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">后台COOKIE作用域</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieadmindomain" type="text" id="cookieadmindomain" value="<?=$ecms_config['cks']['ckadmindomain']?>" size="35"></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">后台COOKIE作用路径</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieadminpath" type="text" id="cookieadminpath" value="<?=$ecms_config['cks']['ckadminpath']?>" size="35"></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">COOKIE的HttpOnly属性</td>
      <td height="25" bgcolor="#FFFFFF"><select name="ckhttponly" id="ckhttponly">
        <option value="0"<?=$ecms_config['cks']['ckhttponly']==0?' selected':''?>>关闭</option>
        <option value="1"<?=$ecms_config['cks']['ckhttponly']==1?' selected':''?>>开启</option>
        <option value="2"<?=$ecms_config['cks']['ckhttponly']==2?' selected':''?>>只后台开启</option>
        <option value="3"<?=$ecms_config['cks']['ckhttponly']==3?' selected':''?>>只前台开启</option>
      </select>      </td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">COOKIE的secure属性</td>
      <td height="25" bgcolor="#FFFFFF"><select name="cksecure" id="cksecure">
        <option value="0"<?=$ecms_config['cks']['cksecure']==0?' selected':''?>>自动识别</option>
		<option value="1"<?=$ecms_config['cks']['cksecure']==1?' selected':''?>>关闭</option>
        <option value="2"<?=$ecms_config['cks']['cksecure']==2?' selected':''?>>开启</option>
        <option value="3"<?=$ecms_config['cks']['cksecure']==3?' selected':''?>>只后台开启</option>
        <option value="4"<?=$ecms_config['cks']['cksecure']==4?' selected':''?>>只前台开启</option>
      </select>
        <font color="#666666">(开启需要https支持)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">前台COOKIE变量前缀</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookievarpre" type="text" id="cookievarpre" value="<?=$ecms_config['cks']['ckvarpre']?>" size="35"> 
        <font color="#666666">(由英文字母组成,5~12个字符组成)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">后台COOKIE变量前缀</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieadminvarpre" type="text" id="cookieadminvarpre" value="<?=$ecms_config['cks']['ckadminvarpre']?>" size="35"> 
        <font color="#666666">(由英文字母组成,5~12个字符组成)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">COOKIE验证随机码</td>
      <td height="25" bgcolor="#FFFFFF"> <input name="cookieckrnd" type="text" id="cookieckrnd" value="<?=$ecms_config['cks']['ckrnd']?>" size="35"> 
        <input type="button" name="Submit32" value="随机" onClick="document.setform.cookieckrnd.value='<?=make_password(36)?>';"> 
        <font color="#666666">(填写10~50个任意字符，最好多种字符组合)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">COOKIE验证随机码2</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieckrndtwo" type="text" id="cookieckrndtwo" value="<?=$ecms_config['cks']['ckrndtwo']?>" size="35">
        <input type="button" name="Submit322" value="随机" onClick="document.setform.cookieckrndtwo.value='<?=make_password(36)?>';">
        <font color="#666666">(填写10~50个任意字符，最好多种字符组合)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">COOKIE验证随机码3</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieckrndthree" type="text" id="cookieckrndthree" value="<?=$ecms_config['cks']['ckrndthree']?>" size="35">
        <input type="button" name="Submit3222" value="随机" onClick="document.setform.cookieckrndthree.value='<?=make_password(36)?>';">
        <font color="#666666">(填写10~50个任意字符，最好多种字符组合)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">COOKIE验证随机码4</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieckrndfour" type="text" id="cookieckrndfour" value="<?=$ecms_config['cks']['ckrndfour']?>" size="35">
        <input type="button" name="Submit32222" value="随机" onClick="document.setform.cookieckrndfour.value='<?=make_password(36)?>';">
        <font color="#666666">(填写10~50个任意字符，最好多种字符组合)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">COOKIE验证随机码5</td>
      <td height="25" bgcolor="#FFFFFF"><input name="cookieckrndfive" type="text" id="cookieckrndfive" value="<?=$ecms_config['cks']['ckrndfive']?>" size="35">
        <input type="button" name="Submit322222" value="随机" onClick="document.setform.cookieckrndfive.value='<?=make_password(36)?>';">
        <font color="#666666">(填写10~50个任意字符，最好多种字符组合)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"></td>
      <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value=" 设 置 "> 
        &nbsp;&nbsp;&nbsp; <input type="reset" name="Submit2" value="重置"></td>
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