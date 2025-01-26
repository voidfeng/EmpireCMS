<?php
if(!defined('InEmpireCMS'))
{
	exit();
}

if(!$title)
{
	$title='系统开启'.($ecms==1?'后台':'').'访问验证';
}
$evpostvname=$ecms==1?'hecmsckviewdof':'qecmsckviewdof';
if($ecmsaddvn)
{
	$evpostvname=$evpostvname.intval($ecmsaddvn);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>访问验证</title>
<style>table{ font: 9pt Tahoma, Verdana; }</style>
</head>
<body>
<form name="form1" method="post" action="" autocomplete="off">
<br><br><br><br><br><br>
<table width="500" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#4FB4DE">
<tr>
	<td height="27">
		<div align="center"><strong><font color="#FFFFFF"><?php echo $title; ?></font></strong></div>
	</td>
</tr>
<tr>
	<td height="36" bgcolor="#FFFFFF">
		<div align="center">请输入访问密码：<input name="eckvpassword" type="password" id="eckvpassword">
		<input type="submit" name="Submit" value="进入"><input name="<?php echo $evpostvname; ?>" type="hidden" id="<?php echo $evpostvname; ?>" value="1"></div>
	</td>
</tr>
</table>
</form>
</body>
</html>