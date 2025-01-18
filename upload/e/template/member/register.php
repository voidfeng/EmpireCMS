<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
$public_diyr['pagetitle']='注册会员';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;注册会员";
include(ECMS_PATH.'e/template/incfile/header.php');
?>
<table width='100%' border='0' align='center' cellpadding='3' cellspacing='1' class="tableborder">
  <form name=userinfoform method=post enctype="multipart/form-data" action=../doaction.php>
    <input type=hidden name=enews value=register>
    <tr class="header"> 
      <td height="25" colspan="2">注册会员<?=$tobind?' (绑定账号)':''?></td>
    </tr>
    <tr> 
      <td height="25" colspan="2"><strong>基本信息 
        <input name="groupid" type="hidden" id="groupid" value="<?=$groupid?>">
        <input name="tobind" type="hidden" id="tobind" value="<?=$tobind?>">
      </strong></td>
    </tr>
    <tr> 
      <td width='25%' height="25" bgcolor="#FFFFFF"> <div align='left'>用户名</div></td>
      <td width='75%' height="25" bgcolor="#FFFFFF"> <input name='username' type='text' id='username' maxlength='30'>
        *</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>密码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='password' type='password' id='password' maxlength='20'>
        *</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>重复密码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='repassword' type='password' id='repassword' maxlength='20'>
        *</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>邮箱</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input name='email' type='text' id='email' maxlength='50'>
        <?=$public_r['regmustef']==1||$public_r['regmustef']==0?'*':''?> </td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">手机号码</td>
      <td height="25" bgcolor="#FFFFFF"><input name='phno' type='text' id='esendphmno' maxlength='20'>
       <?=$public_r['regmustef']==2||$public_r['regmustef']==0?'*':''?> </td>
    </tr>
	<?php
	if($level_r[$groupid]['regps'])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align='left'>注册认证码</div></td>
      <td height="25" bgcolor="#FFFFFF"> <input type='password' name='mg_regps' id='mg_regps'>
        *</td>
    </tr>
	<?php
	}
	?>
    <tr> 
      <td height="25" colspan="2"><strong>其他信息</strong></td>
    </tr>
    <tr> 
      <td height="25" colspan="2" bgcolor="#FFFFFF"> 
    <?php
	@include($formfile);
	?>      </td>
    </tr>
	<?php
	if($public_r['regkey_ok'])
	{
	?>
    <tr>
      <td height="25" bgcolor="#FFFFFF">验证码：</td>
      <td height="25" bgcolor="#FFFFFF">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="52"><input name="key" type="text" id="key" size="6">                  </td>
                  <td id="regshowkey"><a href="#EmpireCMS" onclick="edoshowkey('regshowkey','reg','<?=$public_r['newsurl']?>');" title="点击显示验证码">点击显示验证码</a></td>
                </tr>
            </table>      </td>
    </tr>
	<?php
	}	
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type='submit' name='Submit' value='马上注册'> 
        &nbsp;&nbsp; <input type='button' name='Submit2' value='返回' onclick='history.go(-1)'></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2">说明：带*项为必填。</td>
    </tr>
  </form>
</table>
<?php
include(ECMS_PATH.'e/template/incfile/footer.php');
?>