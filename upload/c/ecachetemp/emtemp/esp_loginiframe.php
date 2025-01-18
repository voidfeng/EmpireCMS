<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
if($mhavelogin==1)
{
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登陆</title>
<LINK href="../../data/images/qcss.css" rel=stylesheet>
</head>
<body bgcolor="#ededed" topmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
	<td height="23" align="center">
	<div align="left">
		&raquo;&nbsp;<font color=red><b><?=$myusername?></b></font>&nbsp;&nbsp;<a href="../my/" target="_parent"><?=$groupname?></a>&nbsp;<?=$havemsg?>&nbsp;<a href="/ecms80/e/space/?userid=<?=$myuserid?>" target=_blank>我的空间</a>&nbsp;&nbsp;<a href="../msg/" target=_blank>短信息</a>&nbsp;&nbsp;<a href="../fava/" target=_blank>收藏夹</a>&nbsp;&nbsp;<a href="../cp/" target="_parent">控制面板</a>&nbsp;&nbsp;<a href="../../member/doaction.php?enews=exit&prtype=9" onclick="return confirm('确认要退出?');">退出</a> 
	</div>
	</td>
    </tr>
</table>
</body>
</html>
<?php
}
else
{
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登陆</title>
<LINK href="../../data/images/qcss.css" rel=stylesheet>
</head>
<body bgcolor="#ededed" topmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <form name=login method=post action="../../member/doaction.php">
    <input type=hidden name=enews value=login>
    <input type=hidden name=prtype value=1>
    <tr> 
      <td height="23" align="center">
      <div align="left">
      用户名：<input name="username" type="text" size="8">&nbsp;
      密码：<input name="password" type="password" size="8">
      <select name="lifetime" id="lifetime">
         <option value="0">不保存</option>
         <option value="3600">一小时</option>
         <option value="86400">一天</option>
         <option value="2592000">一个月</option>
         <option value="315360000">永久</option>
      </select>&nbsp;
      <input type="submit" name="Submit" value="登陆">&nbsp;
      <input type="button" name="Submit2" value="注册" onclick="window.open('../register/');">
      </div>
      </td>
    </tr>
  </form>
</table>
</body>
</html>

<?php
}
?>