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
document.write("&raquo;&nbsp;<font color=red><b><?=$myusername?></b></font>&nbsp;&nbsp;<a href=\"/ecms80/e/member/my/\" target=\"_parent\"><?=$groupname?></a>&nbsp;<?=$havemsg?>&nbsp;<a href=\"/ecms80/e/space/?userid=<?=$myuserid?>\" target=_blank>我的空间</a>&nbsp;&nbsp;<a href=\"/ecms80/e/member/msg/\" target=_blank>短信息</a>&nbsp;&nbsp;<a href=\"/ecms80/e/member/fava/\" target=_blank>收藏夹</a>&nbsp;&nbsp;<a href=\"/ecms80/e/member/cp/\" target=\"_parent\">控制面板</a>&nbsp;&nbsp;<a href=\"/ecms80/e/member/doaction.php?enews=exit&ecmsfrom=9\" onclick=\"return confirm(\'确认要退出?\');\">退出</a>");
<?php
}
else
{
?>
document.write("<form name=login method=post action=\"/ecms80/e/member/doaction.php\">    <input type=hidden name=enews value=login>    <input type=hidden name=ecmsfrom value=9>    用户名：<input name=\"username\" type=\"text\" class=\"inputText\" size=\"16\" />&nbsp;    密码：<input name=\"password\" type=\"password\" class=\"inputText\" size=\"16\" />&nbsp;    <input type=\"submit\" name=\"Submit\" value=\"登陆\" class=\"inputSub\" />&nbsp;    <input type=\"button\" name=\"Submit2\" value=\"注册\" class=\"inputSub\" onclick=\"window.open(\'/ecms80/e/member/register/\');\" /></form>");
<?php
}
?>