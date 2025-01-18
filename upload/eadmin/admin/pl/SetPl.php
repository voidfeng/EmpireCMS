<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//验证权限
CheckLevel($logininid,$loginin,$classid,"public");
$r=$empire->fetch1("select * from {$dbtbpre}enewspl_set".do_dblimit_one());
//评论权限
$plgroup='';
/*
$mgsql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($mgr=$empire->fetch($mgsql))
{
	if($r['plgroupid']==$mgr['groupid'])
	{
		$plgroup_select=' selected';
	}
	else
	{
		$plgroup_select='';
	}
	$plgroup.="<option value=".$mgr['groupid'].$plgroup_select.">".$mgr['groupname']."</option>";
}
*/
//返回会员组+访问组
$ymvgroup=eReturnMVGroupFormSelect(0);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>评论参数设置</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32"><p>位置：评论参数设置</p>
    </td>
  </tr>
</table>
<form name="plset" method="post" action="../ecmspl.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('SetPl'); ?>
    <tr class="header"> 
      <td height="25" colspan="2">评论参数设置 
        <input name=enews type=hidden value=SetPl></td>
    </tr>
	<tr>
      <td height="25" bgcolor="#FFFFFF">评论地址</td>
      <td height="25" bgcolor="#FFFFFF"><input name="plurl" type="text" id="plurl" value="<?=$r['plurl']?>" size="38">
        <font color="#666666">(绑定域名时设置，结尾需加“/”，如：/e/pl/)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">评论权限限制</td>
      <td height="25"><select name="plgroupid" id="plgroupid">
          <option value="0"<?php echo $r['plgroupid']==0?' selected':'';?>>游客</option>
          <?php echo str_replace(" value=".$r['plgroupid'].">"," value=".$r['plgroupid']." selected>",$ymvgroup);?>
          <option value="-99999999"<?php echo $r['plgroupid']==-99999999?' selected':'';?>>关闭</option>
        </select></td>
    </tr>
	<tr bgcolor="#FFFFFF"> 
      <td height="25">新会员评论限制</td>
      <td height="25">新注册会员必须过<input name="plnewaddtime" type="text" id="plnewaddtime" value="<?=$r['plnewaddtime']?>" size="6">
	  分钟才能评论 <font color="#666666">(0为不限制)</font>
      </td>
    </tr>
	<tr bgcolor="#FFFFFF"> 
      <td height="25">评论时登录会员</td>
      <td height="25"><input type="radio" name="plcanlogin" value="1"<?=$r['plcanlogin']==1?' checked':''?>>
        开启 
        <input type="radio" name="plcanlogin" value="0"<?=$r['plcanlogin']==0?' checked':''?>>
        关闭</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">评论内容限制</td>
      <td width="81%" height="25"><input name="plsize" type="text" id="plsize" value="<?=$r['plsize']?>" size="38">
        个字节<font color="#666666"> (两个字节为一个汉字)</font> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">评论时间间隔</td>
      <td height="25"><input name="pltime" type="text" id="pltime" value="<?=$r['pltime']?>" size="38">
        秒</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">评论验证码</td>
      <td height="25"><input type="radio" name="plkey_ok" value="1"<?=$r['plkey_ok']==1?' checked':''?>>
        开启 
        <input type="radio" name="plkey_ok" value="0"<?=$r['plkey_ok']==0?' checked':''?>>
        关闭</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">评论每页显示</td>
      <td height="25"><input name="pl_num" type="text" id="pl_num" value="<?=$r['pl_num']?>" size="38">
        个评论</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">评论表情每行显示</td>
      <td height="25"><input name="plfacenum" type="text" id="plfacenum" value="<?=$r['plfacenum']?>" size="38">
        个表情</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">评论屏蔽字符<br> <font color="#666666">(1)、多个用“|”隔开，如“字符1|字符2”。<br>
        (2)、同时包含多字时屏蔽可用双“#”隔开，如“破##解|字符2” 。这样只要内容同时包含“破”和“解”字都会被屏蔽。</font></td>
      <td height="25"><textarea name="plclosewords" cols="80" rows="8" id="plclosewords"><?=ehtmlspecialchars($r['plclosewords'])?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">评论盖楼最高楼层</td>
      <td height="25"><input name="plmaxfloor" type="text" id="plmaxfloor" value="<?=$r['plmaxfloor']?>" size="38">
        楼 <font color="#666666">(0为不限，-1为只保留一层引用，-2为不支持引用)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" valign="top">评论引用内容格式：<br>
      <br>
      评论ID：[!--plid--]<br>
      发表者：[!--username--]<br>
      评论内容：[!--pltext--]<br>
      发表时间：[!--pltime--]</td>
      <td height="25"><textarea name="plquotetemp" cols="80" rows="8" id="plquotetemp"><?=ehtmlspecialchars(stripSlashes($r['plquotetemp']))?></textarea></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>