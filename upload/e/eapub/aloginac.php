<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台登录激活</title>
<style>
a						{ text-decoration: none; color: #002280 }
a:hover					{ text-decoration: underline }
body					{ font-size: 9pt; }
table					{ font: 9pt Tahoma, Verdana; color: #000000 }
input,select,textarea	{ font: 9pt Tahoma, Verdana; font-weight: normal; }
select					{ font: 9pt Tahoma, Verdana; font-weight: normal; }
.nav					{ font: 9pt Tahoma, Verdana; color: #000000; font-weight: bold }
.nav a					{ color: #000000 }
.header					{ font: 9pt Tahoma, Verdana; color: #FFFFFF; font-weight: bold; background-color: #4FB4DE }
.header a				{ color: #FFFFFF }
.category				{ font: 9pt Tahoma, Verdana; color: #000000; background-color: #fcfcfc }
.tableborder			{ background: #C9F1FF; border: 1px solid #4FB4DE } 
.singleborder			{ font-size: 0px; line-height: 1px; padding: 0px; background-color: #F8F8F8 }
.smalltxt				{ font: 9pt Tahoma, Verdana }
.outertxt				{ font: 9pt Tahoma, Verdana; color: #000000 }
.outertxt a				{ color: #000000 }
.bold					{ font-weight: bold }
</style>
</head>

<body>

<br>
<br>
<br>
<br>
<br>
<br>
<table width="800" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td height="25"><div align="center">账号需要激活</div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="80"> 
      <div align="center">

    <br><br><div align="center">您的账号需要激活才能进后台，请联系管理员帮您激活。<br>
    <br><br>
  用户ID：<strong><?=$lgacuserid?></strong> ，这是您第 <strong><?=$lgacloginnum?></strong> 次登录，本次登录时间：<strong><?=$lgaclasttime?></strong><br><br>
    <br>如果已经激活，请 <strong><a href="#empirecms" onClick="window.location.reload();"><font color="#FF0000">点击这里</font></a></strong> 或<font color="#FF0000"><strong> 刷新本网页</strong></font> 重新加载页面。</div><br><br>

	  </div></td>
  </tr>
</table>
</body>
</html>
