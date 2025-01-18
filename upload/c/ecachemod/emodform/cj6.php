<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>商品名称正则：</strong><br>
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
    <td height="22" valign="top"><strong>商品编号正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--productno--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_productno]" cols="60" rows="10" id="zz_productno"><?=ehtmlspecialchars(stripSlashes($r['zz_productno']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_productno]" type="text" id="z_productno" value="<?=stripSlashes($r['z_productno'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>品牌正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--pbrand--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_pbrand]" cols="60" rows="10" id="zz_pbrand"><?=ehtmlspecialchars(stripSlashes($r['zz_pbrand']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_pbrand]" type="text" id="z_pbrand" value="<?=stripSlashes($r['z_pbrand'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>简单描述正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--intro--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_intro]" cols="60" rows="10" id="zz_intro"><?=ehtmlspecialchars(stripSlashes($r['zz_intro']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_intro]" type="text" id="z_intro" value="<?=stripSlashes($r['z_intro'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>计量单位正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--unit--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_unit]" cols="60" rows="10" id="zz_unit"><?=ehtmlspecialchars(stripSlashes($r['zz_unit']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_unit]" type="text" id="z_unit" value="<?=stripSlashes($r['z_unit'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>单位重量正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--weight--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_weight]" cols="60" rows="10" id="zz_weight"><?=ehtmlspecialchars(stripSlashes($r['zz_weight']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_weight]" type="text" id="z_weight" value="<?=stripSlashes($r['z_weight'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>市场价格正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--tprice--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_tprice]" cols="60" rows="10" id="zz_tprice"><?=ehtmlspecialchars(stripSlashes($r['zz_tprice']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_tprice]" type="text" id="z_tprice" value="<?=stripSlashes($r['z_tprice'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>购买价格正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--price--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_price]" cols="60" rows="10" id="zz_price"><?=ehtmlspecialchars(stripSlashes($r['zz_price']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_price]" type="text" id="z_price" value="<?=stripSlashes($r['z_price'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>积分购买正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--buyfen--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_buyfen]" cols="60" rows="10" id="zz_buyfen"><?=ehtmlspecialchars(stripSlashes($r['zz_buyfen']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_buyfen]" type="text" id="z_buyfen" value="<?=stripSlashes($r['z_buyfen'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>库存正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--pmaxnum--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_pmaxnum]" cols="60" rows="10" id="zz_pmaxnum"><?=ehtmlspecialchars(stripSlashes($r['zz_pmaxnum']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_pmaxnum]" type="text" id="z_pmaxnum" value="<?=stripSlashes($r['z_pmaxnum'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>商品缩略片正则：</strong><br>
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
    <td height="22" valign="top"><strong>商品大图正则：</strong><br>
      ( 
      <input name="textfield" type="text" id="textfield" value="[!--productpic--]" size="20">
      )</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td>附件前缀 
        <input name="add[qz_productpic]" type="text" id="qz_productpic" value="<?=stripSlashes($r['qz_productpic'])?>"> 
        <input name="add[save_productpic]" type="checkbox" id="save_productpic" value=" checked"<?=$r['save_productpic']?>>
        远程保存 </td>
    </tr>
    <tr> 
      <td><textarea name="add[zz_productpic]" cols="60" rows="10" id="zz_productpic"><?=ehtmlspecialchars(stripSlashes($r['zz_productpic']))?></textarea></td>
    </tr>
    <tr> 
      <td><input name="add[z_productpic]" type="text" id="z_productpic" value="<?=stripSlashes($r['z_productpic'])?>">
        (如填写这里，这就是字段的值)</td>
    </tr>
  </table></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="22" valign="top"><strong>商品介绍正则：</strong><br>
      (<input name="textfield" type="text" id="textfield" value="[!--newstext--]" size="20">)</td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td><textarea name="add[zz_newstext]" cols="60" rows="10" id="zz_newstext"><?=ehtmlspecialchars(stripSlashes($r['zz_newstext']))?></textarea></td>
        </tr>
        <tr> 
          <td><input name="add[z_newstext]" type="text" id="z_newstext" value="<?=stripSlashes($r['z_newstext'])?>">
            (如填写这里，将为字段的值)</td>
        </tr>
      </table></td>
  </tr>
