<?php
error_reporting(0);

@header('Content-Type: text/html; charset=utf-8');
if(file_exists("../../c/einstall/install.off"))
{
	echo"《帝国网站管理系统》安装程序已锁定。如果要重新安装，请删除<b>/c/einstall/install.off</b>文件！";
	exit();
}

//安装密码
$ins_password='';
$ins_pwvar='';
$ins_insretime=0;
include('data/fun.php');
include('ins_config.php');

if($_POST['ecms']=='inslogin')
{
	eins_CkFormInsPass($_POST['inspass']);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国网站管理系统安装程序 - Powered by EmpireCMS</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
</head>

<body>
  <p>&nbsp;</p>
  <p><br>
  </p>
  <table width="660" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="inslogin.php">
  <input name="ecms" type="hidden" id="ecms" value="inslogin">
    <tr class="header">
      <td height="32"><div align="center">帝国网站管理系统安装程序</div></td>
    </tr>
	<?php
	if(!$ins_password)
	{
	?>
    <tr>
      <td height="32" bgcolor="#FFFFFF"><div align="center"><font color="#FF0000"><strong>提示：您还没有设置安装密码，无法进入。</strong></font></div></td>
    </tr>
	<?php
	}
	?>
    <tr>
      <td height="32" bgcolor="#FFFFFF"><div align="center">请输入安装密码：
          <input name="inspass" type="password" id="inspass" size="30">
          <input type="submit" name="Submit" value="进入安装程序">
      </div></td>
    </tr>
    <tr>
      <td height="32" bgcolor="#FFFFFF"><div align="center"><font color="#666666">（安装密码设置：修改 /e/install/ins_config.php 文件里的“$ins_password”变量内容）</font></div></td>
    </tr>
	</form>
  </table>
</body>
</html>
