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
$enews=ehtmlspecialchars($_GET['enews']);
$userid=(int)$_GET['userid'];
$id=(int)$_GET['id'];
if(!$userid)
{
	printerror('ErrorUrl','');
}
if($logininid==$userid)
{
}
else
{
	CheckLevel($logininid,$loginin,$classid,"user");
}

$addur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='$userid'");
if(!$addur['userid'])
{
	printerror('ErrorUrl','');
}
$word='增加一次性密码';
if($enews=='EditUserOnePass')
{
	$enews='EditUserOnePass';
}
else
{
	$enews='AddUserOnePass';
}
//formhash
$efh=heformhash_get($enews);
eCheckStrType(4,$enews,1);
if($enews=="EditUserOnePass")
{
	if(!$id)
	{
		printerror('ErrorUrl','');
	}
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuseronepass where id='$id' and userid='$userid'");
	if(!$r['userid'])
	{
		printerror('ErrorUrl','');
	}
	$word='修改一次性密码';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$word?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function selectalls(doselect,formvar)
{  
	 var bool=doselect==1?true:false;
	 var selectform=document.getElementById(formvar);
	 for(var i=0;i<selectform.length;i++)
	 { 
		  selectform.options[i].selected=bool;
	 } 
}
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<a href="ListUser.php<?=$ecms_hashur['whehref']?>">管理用户</a> &gt; 用户：<?=$addur['username']?> &gt; <a href="ListUserOnePass.php?userid=<?=$userid?><?=$ecms_hashur['ehref']?>">管理一次性密码</a> &gt; <?=$word?></td>
  </tr>
</table>
<form name="form1" method="post" action="ListUserOnePass.php" autocomplete="off">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2">增加一次性密码 
        <input name="userid" type="hidden" id="userid" value="<?=$userid?>"> 
		<input name="id" type="hidden" id="id" value="<?=$id?>">
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">用户名：</td>
      <td height="25"><strong><?=$addur['username']?></strong>&nbsp;&nbsp; (用户ID： <strong><?=$userid?></strong>) </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="22%" height="25">密码识别码：</td>
      <td width="78%" height="25"><input name="pno" type="text" id="pno" value="<?=$r['pno']?>" size="32">
        *<font color="#666666"> (最大30字节)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">是否启用：</td>
      <td height="25"><input name="isopen" type="checkbox" id="isopen" value="1"<?=$r['isopen']==1?' checked':''?>>
        启用</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">密码：</td>
      <td height="25"><input name="password" type="password" id="password" size="32">
		<?php
		if($enews=="EditUserOnePass")
		{
		?>
		* <font color="#666666">(不想修改请留空)</font>
		<?php
		}
		?>
		</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">重复密码：</td>
      <td height="25"><input name="repassword" type="password" id="repassword" size="32">
		<?php
		if($enews=="EditUserOnePass")
		{
		?>
		* <font color="#666666">(不想修改请留空)</font>
		<?php
		}
		?>
		</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">&nbsp;</td>
      <td height="25"><font color="#666666">(说明：密码设置8~30位，区分大小写，且密码不能包含：$ 
      &amp; * # &lt; &gt; ' &quot; / \ % ; 空格)</font></td>
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