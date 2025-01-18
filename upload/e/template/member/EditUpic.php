<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
$public_diyr['pagetitle']='修改头像';
$url="<a href=../../../>首页</a>&nbsp;>&nbsp;<a href=../cp/>会员中心</a>&nbsp;>&nbsp;修改头像";
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
<table width='600' border='0' align='center' cellpadding='3' cellspacing='1' class="tableborder">
  <form name=userinfoform method=post enctype="multipart/form-data" action="../doaction.php" onsubmit="return confirm('确认要修改？');">
    <input type=hidden name=enews value=EditUpic>
    <tr class="header"> 
      <td height="25" colspan="2">修改头像</td>
    </tr>
    <tr> 
      <td width="21%" rowspan="3" bgcolor="#FFFFFF">
	    <div align="center">
		<?php
		$mupicurl=eMember_UpicReturnUrl($user['userid'],$user['upic']);
		?>
		<img src="<?=$mupicurl?>" width="80" height="80" border="1">
        </div></td>
      <td width="79%" height="30" bgcolor="#FFFFFF">用户名：<?=$user['username']?></td>
    </tr>
    <tr>
      <td height="32" bgcolor="#FFFFFF"><input type="file" name="file">
        <input type='submit' name='Submit' value='上传新头像'></td>
    </tr>
    <tr>
      <td height="28" bgcolor="#FFFFFF"><font color="#666666">说明：文件允许扩展名：<b>
        <?=str_replace('|',',',$public_r['upictype'])?>
      </b>，文件大小不超过：<b>
      <?=$public_r['upicsize']?>
      </b>KB</font></td>
    </tr>
  </form>
</table>
<?php
include(ECMS_PATH.'e/template/incfile/footer.php');
?>