<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
ehCheckCloseMods('loadinmod');
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
CheckLevel($logininid,$loginin,$classid,"table");
$url="<a href=ListTable.php".$ecms_hashur['whehref'].">管理数据表</a>&nbsp;>&nbsp;导入系统模型";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>导入系统模型</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="32">位置： 
      <?=$url?>    </td>
  </tr>
</table>
<form action="../ecmsmod.php" method="post" enctype="multipart/form-data" name="form1" onsubmit="return confirm('确认要导入?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('LoadInMod'); ?>
    <tr> 
      <td height="25" colspan="2" class="header">导入系统模型</td>
    </tr>
    <tr> 
      <td width="28%" height="25" bgcolor="#FFFFFF">存放的数据表名:</td>
      <td width="72%" height="25" bgcolor="#FFFFFF"><strong><?=$dbtbpre?>ecms_</strong> 
        <input name="tbname" type="text" id="tbname">
      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">选择导入模型文件:</td>
      <td height="25" bgcolor="#FFFFFF"><input type="file" name="file">
        *.mod</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="马上导入"> 
        <input type="reset" name="Submit2" value="重置">
        <input name="enews" type="hidden" id="enews" value="LoadInMod">
      </td>
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