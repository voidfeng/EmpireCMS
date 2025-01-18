<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>标题正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--title--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_title]" cols="60" rows="10" id="zz_title"><?=ehtmlspecialchars(stripSlashes($r['zz_title']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_title]" type="text" id="z_title" value="<?=stripSlashes($r['z_title'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>发布时间正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--newstime--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_newstime]" cols="60" rows="10" id="zz_newstime"><?=ehtmlspecialchars(stripSlashes($r['zz_newstime']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_newstime]" type="text" id="z_newstime" value="<?=stripSlashes($r['z_newstime'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>信息内容正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--smalltext--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_smalltext]" cols="60" rows="10" id="zz_smalltext"><?=ehtmlspecialchars(stripSlashes($r['zz_smalltext']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_smalltext]" type="text" id="z_smalltext" value="<?=stripSlashes($r['z_smalltext'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>图片正则：</strong><br>
      ( 
      <input name="textfield" type="text" id="textfield" value="[!--titlepic--]" size="20">
      )</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td>附件前缀 
        <input name="add[qz_titlepic]" type="text" id="qz_titlepic" value="<?=stripSlashes($r['qz_titlepic'])?>"> 
        <input name="add[save_titlepic]" type="checkbox" id="save_titlepic" value=" checked"<?=$r['save_titlepic']?>>
        远程保存 </td>
    </tr>
    <tr> 
      <td><textarea name="add[zz_titlepic]" cols="60" rows="10" id="zz_titlepic"><?=ehtmlspecialchars(stripSlashes($r['zz_titlepic']))?></textarea></td>
    </tr>
    <tr> 
      <td><input name="add[z_titlepic]" type="text" id="z_titlepic" value="<?=stripSlashes($r['z_titlepic'])?>">
        (如填写这里，这就是字段的值)</td>
    </tr>
  </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>所在地正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--myarea--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_myarea]" cols="60" rows="10" id="zz_myarea"><?=ehtmlspecialchars(stripSlashes($r['zz_myarea']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_myarea]" type="text" id="z_myarea" value="<?=stripSlashes($r['z_myarea'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>联系邮箱正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--email--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_email]" cols="60" rows="10" id="zz_email"><?=ehtmlspecialchars(stripSlashes($r['zz_email']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_email]" type="text" id="z_email" value="<?=stripSlashes($r['z_email'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>联系方式正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--mycontact--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_mycontact]" cols="60" rows="10" id="zz_mycontact"><?=ehtmlspecialchars(stripSlashes($r['zz_mycontact']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_mycontact]" type="text" id="z_mycontact" value="<?=stripSlashes($r['z_mycontact'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>联系地址正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--address--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_address]" cols="60" rows="10" id="zz_address"><?=ehtmlspecialchars(stripSlashes($r['zz_address']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_address]" type="text" id="z_address" value="<?=stripSlashes($r['z_address'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>
