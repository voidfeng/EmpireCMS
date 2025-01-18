<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../../../../e/data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../../../../e/data/menu/menu.js" type="text/javascript"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
</head>
<body onLoad="initialize()">
<table border='0' cellspacing='0' cellpadding='0'>
	<tr height=20>
			<td id="home"><img src="../../../../e/data/images/homepage.gif" border=0></td>
			<td><b>用户面板</b></td>
	</tr>
</table>

<table border='0' cellspacing='0' cellpadding='0'>
  <tr> 
    <td id="pruser" class="menu1" onClick="chengstate('user')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">用户管理</a>
	</td>
  </tr>
  <tr id="itemuser" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
          <td class="file">
			<a href="../../user/EditPassword.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">修改个人资料</a>
          </td>
        </tr>
		<?php
		if($r['docklgac'])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../user/ListLgacUser.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理后台登录激活</a>
          </td>
        </tr>
		<?php
		}
		if($r['dogroup'])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../user/ListGroup.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理用户组</a>
          </td>
        </tr>
		<?php
		}
		if($r['douserclass'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../user/UserClass.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理部门</a>
          </td>
        </tr>
		<?php
		}
		if($r['douser'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../user/ListUser.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理用户</a>
          </td>
        </tr>
		<?php
		}
		if($r['dolog'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../user/ListLog.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理登录日志</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../user/ListDolog.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理操作日志</a>
          </td>
        </tr>
		<?php
		}
		if($r['doadminstyle'])
		{
		?>
		<tr> 
          <td class="file1">
			<a href="../../template/AdminStyle.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理后台风格</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>

<?php
if($r['domember']||$r['domemberf'])
{
?>
  <tr> 
    <td id="prmember" class="menu1" onClick="chengstate('member')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">会员管理</a>
	</td>
  </tr>
  <tr id="itemmember" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['domember'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListMember.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理会员</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../member/ListMemberMore.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理会员(详细)</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../member/ClearMember.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量清理会员</a>
          </td>
        </tr>
		<?php
		}
		if($r['domembergroup'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListMemberGroup.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">会员组</a>
          </td>
        </tr>
		<?php
		}
		if($r['doingroup'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListInGroup.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">会员内部组</a>
          </td>
        </tr>
		<?php
		}
		if($r['doviewgroup'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListViewGroup.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">会员访问组</a>
          </td>
        </tr>
		<?php
		}
		if($r['domadmingroup'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListMAdminGroup.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">会员管理组</a>
          </td>
        </tr>
		<?php
		}
		if($r['domemberf'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListMemberF.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理会员字段</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../member/ListMemberForm.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理会员表单</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['dospacestyle']||$r['dospacedata'])
{
?>
  <tr> 
    <td id="prmemberspace" class="menu1" onClick="chengstate('memberspace')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">会员空间管理</a>
	</td>
  </tr>
  <tr id="itemmemberspace" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['dospacestyle'])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../member/ListSpaceStyle.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理空间模板</a>
          </td>
        </tr>
		<?php
		}
		if($r['dospacedata'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/MemberGbook.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理空间留言</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../member/MemberFeedback.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理空间反馈</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['domemberconnect'])
{
?>
  <tr> 
    <td id="prmemberconnect" class="menu1" onClick="chengstate('memberconnect')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">外部接口</a>
	</td>
  </tr>
  <tr id="itemmemberconnect" style="display:none"> 
    <td class="list">
		<table border='0' cellspacing='0' cellpadding='0'>
		<tr> 
          <td class="file1">
			<a href="../../member/MemberConnect.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理外部登录接口</a>
          </td>
        </tr>
      </table>
	</td>
  </tr>
<?php
}
?>

<?php
if($r['docard']||$r['dosendemail']||$r['domsg']||$r['dobuygroup'])
{
?>
  <tr> 
    <td id="prmother" class="menu3" onClick="chengstate('mother')">
		<a onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">其他功能</a>
	</td>
  </tr>
  <tr id="itemmother" style="display:none"> 
    <td class="list1">
		<table border='0' cellspacing='0' cellpadding='0'>
		<?php
		if($r['dobuygroup'])
		{
		?>
        <tr> 
          <td class="file">
			<a href="../../member/ListBuyGroup.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理充值类型</a>
          </td>
        </tr>
		<?php
		}
		if($r['docard'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/ListCard.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">管理点卡</a>
          </td>
        </tr>
		<tr> 
          <td class="file">
			<a href="../../member/GetFen.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量赠送点数</a>
          </td>
        </tr>
		<?php
		}
		if($r['dosendemail'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/SendEmail.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量发送邮件</a>
          </td>
        </tr>
		<?php
		}
		if($r['domsg'])
		{
		?>
		<tr> 
          <td class="file">
			<a href="../../member/SendMsg.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量发送短消息</a>
          </td>
        </tr>
		<tr> 
          <td class="file1">
			<a href="../../member/DelMoreMsg.php<?=$ecms_hashur['whehref']?>" target="main" onMouseOut="this.style.fontWeight=''" onMouseOver="this.style.fontWeight='bold'">批量删除短消息</a>
          </td>
        </tr>
		<?php
		}
		?>
      </table>
	</td>
  </tr>
<?php
}
?>
</table>
<br>
</body>
</html>