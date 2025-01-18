<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
$public_diyr['pagetitle']='修改资料';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;修改安全信息";
include(ECMS_PATH.'e/template/incfile/header.php');
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr>
    <td width="50%" height="30" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="50%" bgcolor="#FFFFFF"><div align="right">[<a href="../EditInfo/">修改基本资料</a>]&nbsp;&nbsp;
	<?php
if($public_r['phmopen'])
{
?>
[<a href="../../extenddef/esms/BindPh.php">绑定手机</a>]&nbsp;&nbsp;
<?php
}
?>
	</div></td>
  </tr>
</table>
<br>
<table width='100%' border='0' align='center' cellpadding='3' cellspacing='1' class="tableborder">
  <form name=userinfoform method=post enctype="multipart/form-data" action="../doaction.php" onsubmit="return confirm('确认要修改？');">
    <input type=hidden name=enews value=EditSafeInfo>
    <tr class="header"> 
      <td height="25" colspan="2">修改安全信息</td>
    </tr>
    <tr> 
      <td width='21%' height="25" bgcolor="#FFFFFF"> <div align='left'>用户名 </div></td>
      <td width='79%' height="25" bgcolor="#FFFFFF"> 
        <?=$user['username']?>      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>原密码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='oldpassword' type='password' id='oldpassword' size="38" maxlength='20'>
        <font color="#666666">(修改密码或邮箱手机时需要密码验证)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>新密码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='password' type='password' id='password' size="38" maxlength='20'>
        <font color="#666666">(不想修改请留空)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>确认新密码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='repassword' type='password' id='repassword' size="38" maxlength='20'>
        <font color="#666666">        (不想修改请留空)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>邮箱</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='email' type='text' id='email' value='<?=$user['email']?>' size="38" maxlength='50'>
	  <?=$public_r['regmustef']==1||$public_r['regmustef']==0?'*':''?> </td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">手机号码</td>
      <td height="25" bgcolor="#FFFFFF"><input name='phno' type='text' id='esendphmno' value='<?=$user['phno']?>' size="38" maxlength='20'>
	  <?=$public_r['regmustef']==2||$public_r['regmustef']==0?'*':''?> </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type='submit' name='Submit' value='修改信息'>      </td>
    </tr>
  </form>
</table>
<?php
include(ECMS_PATH.'e/template/incfile/footer.php');
?>