<?php
define('EmpireCMSAdmin','1');
require('../../../e/class/connect.php');
require('../../../e/class/functions.php');
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
CheckLevel($logininid,$loginin,$classid,"zt");
$sql=$empire->query("select classid,classname from {$dbtbpre}enewsztclass order by classid desc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32"><p>位置：<a href="ListZt.php<?=$ecms_hashur['whehref']?>">管理专题</a> &gt; <a href="ListZtClass.php<?=$ecms_hashur['whehref']?>">管理专题分类</a></p>
    </td>
  </tr>
</table>
<form name="form1" method="post" action="../ecmscom.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('AddZtClass'); ?>
    <tr class="header">
      <td height="25">增加专题分类: 
        <input name=enews type=hidden id="enews" value=AddZtClass>
        <input name=doing type=hidden value=zt>
      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> 类别名称: 
        <input name="classname" type="text" id="classname">
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header">
    <td width="10%"><div align="center">ID</div></td>
    <td width="59%" height="25"><div align="center">类别名称</div></td>
    <td width="31%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('EditZtClass');
  $efh2=heformhash_get('DelZtClass',1);
  
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=../ecmscom.php>
	  <?=$ecms_hashur['form']?>
	  <?php echo $efh; ?>
    <input type=hidden name=enews value=EditZtClass>
	<input name=doing type=hidden value=zt>
    <input type=hidden name=classid value=<?=$r['classid']?>>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'">
      <td><div align="center"><?=$r['classid']?></div></td>
      <td height="25"> <div align="center">
          <input name="classname" type="text" id="classname" value="<?=$r['classname']?>">
        </div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="self.location.href='../ecmscom.php?enews=DelZtClass&classid=<?=$r['classid']?>&doing=zt<?=$ecms_hashur['href'].$efh2?>';">
        </div></td>
    </tr>
  </form>
  <?php
  }
  db_close();
  $empire=null;
  ?>
</table>
<br>
</body>
</html>