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
CheckLevel($logininid,$loginin,$classid,"table");
$enews=RepPostStr($_GET['enews'],1);
eCheckStrType(4,$enews,1);
if($enews=='EditModList')
{
	$enews='EditModList';
}
else
{
	$enews='AddModList';
}
//formhash
$efh=heformhash_get($enews);

$r=array();
$lid=0;
$modlistedit=(int)$_GET['modlistedit'];
$url="<a href=ListModList.php".$ecms_hashur['whehref'].">管理系统模型列表</a>&nbsp;>&nbsp;增加系统模型列表";
$word='增加系统模型列表';
$word2='基本信息';
$editpn='';
$editfn='';
//内容
$aflist='';
$temptext='';
$filetext='';
//修改
if($enews=="EditModList")
{
	$lid=(int)$_GET['lid'];
	$url="<a href=ListModList.php".$ecms_hashur['whehref'].">管理系统模型列表</a>&nbsp;>&nbsp;修改系统模型列表";
	$word='修改系统模型列表';
	$r=$empire->fetch1("select * from {$dbtbpre}enewsmodlist where lid='$lid'");
	$r['lpath']=(int)$r['lpath'];
	if(!$r['lid']||!$r['lpath'])
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	$editpn='c/ecachemod/emodlist/'.$r['lpath'].'/';
	if($modlistedit==1)
	{
		$word2='后台按表管理信息列表样式';
		$editfn=$editpn.'alllistinfo.php';
		$aflist=$r['qafall'];
		$temptext=$r['tempall'];
	}
	elseif($modlistedit==2)
	{
		$word2='后台按栏目管理信息列表样式';
		$editfn=$editpn.'listinfo.php';
		$aflist=$r['qafclass'];
		$temptext=$r['tempclass'];
	}
	elseif($modlistedit==3)
	{
		$word2='后台管理归档信息列表样式';
		$editfn=$editpn.'doclistinfo.php';
		$aflist=$r['qafdoc'];
		$temptext=$r['tempdoc'];
	}
	elseif($modlistedit==4)
	{
		$word2='前台会员管理信息列表样式';
		$editfn=$editpn.'qlistinfo.php';
		$aflist=$r['qafqinfo'];
		$temptext=$r['tempqinfo'];
	}
	else
	{
		$word2='基本信息';
		$editfn='';
	}
	//模板内容
	if(!$temptext)
	{
	}
	//文件内容
	if($editfn)
	{
		if(file_exists('../../../'.$editfn))
		{
			$filetext=ReadFiletext('../../../'.$editfn);
		}
	}
	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $word; ?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="32">位置： 
      <?=$url?>    </td>
  </tr>
</table>
<?php
if($enews=="EditModList")
{
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
<form name="modlistch" method="post" action="">
  <tr>
    <td width="50%" bgcolor="#FFFFFF">列表名称：<strong><?=$r['lname']?></strong> （目录：<strong><?=$r['lpath']?></strong>）</td>
    <td width="50%" bgcolor="#FFFFFF"><div align="right">选择修改项：
      <select name="modlistedit" id="modlistedit" onchange=window.location='AddModList.php?<?=$ecms_hashur['ehref']?>&enews=EditModList&lid=<?=$lid?>&modlistedit='+this.options[this.selectedIndex].value>
        <option value="0"<?=$modlistedit==0?' selected':''?>>基本信息</option>
        <option value="1"<?=$modlistedit==1?' selected':''?>>后台按表管理信息列表样式</option>
        <option value="2"<?=$modlistedit==2?' selected':''?>>后台按栏目管理信息列表样式</option>
        <option value="3"<?=$modlistedit==3?' selected':''?>>后台管理归档信息列表样式</option>
        <option value="4"<?=$modlistedit==4?' selected':''?>>前台会员管理信息列表样式</option>
        </select>
    </div></td>
  </tr>
</form>
</table>
  <?php
  if($modlistedit==0)
  {
  ?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="modlistform" method="post" action="../ecmsmod.php" onSubmit="return confirm('确认要修改？');">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr> 
      <td height="25" colspan="2" class="header"><?=$word?> (<?=$word2?>)</td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">列表目录:</td>
      <td height="25" bgcolor="#FFFFFF"><strong>/<?=$editpn?></strong></td>
    </tr>
    <tr> 
      <td width="23%" height="25" bgcolor="#FFFFFF">列表名称:</td>
      <td width="77%" height="25" bgcolor="#FFFFFF"> <input name="lname" type="text" id="lname" value="<?=$r['lname']?>" size="38">
      *</td>
    </tr>
    <tr> 
      <td height="25" valign="top" bgcolor="#FFFFFF">列表说明:</td>
      <td height="25" bgcolor="#FFFFFF"> <textarea name="lsay" cols="70" rows="8" id="lsay"><?=stripSlashes($r['lsay'])?></textarea></td>
    </tr>
    <tr>
      <td height="25" valign="top" bgcolor="#FFFFFF">是否更新列表文件:</td>
      <td height="25" bgcolor="#FFFFFF"><input type="radio" name="ldoup" value="1"<?=$r['ldoup']==1?' checked':''?>>更新 
      <input type="radio" name="ldoup" value="0"<?=$r['ldoup']==0?' checked':''?>>不更新</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="修改"> 
         <input type="reset" name="Submit2" value="重置"> <input name="lid" type="hidden" id="lid" value="<?=$lid?>"> 
      <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
      <input name="ecms" type="hidden" id="ecms" value="<?=$modlistedit?>"></td>
    </tr>
	</form>
</table>
  <?php
  }
  else
  {
  ?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="modlistform" method="post" action="../ecmsmod.php" onSubmit="return confirm('确认要修改？');">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr> 
      <td height="25" colspan="2" class="header"><?=$word?> (<?=$word2?>)</td>
    </tr>
	<?php
	if(!$r['ldoup'])
	{
	?>
    <tr>
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"><font color="#FF0000">提示：由于<strong>基本信息</strong>里选择了“<strong>不更新列表文件</strong>”，所以下面的操作并不会修改文件。</font></td>
    </tr>
    <?php
	}
	?>
	<tr>
      <td height="25" bgcolor="#FFFFFF">列表目录的文件:</td>
      <td height="25" bgcolor="#FFFFFF"><strong>/<?=$editfn?></strong></td>
    </tr>
    <tr> 
      <td width="23%" height="25" bgcolor="#FFFFFF">增加查询字段:</td>
      <td width="77%" height="25" bgcolor="#FFFFFF"> <input name="aflist" type="text" id="aflist" value="<?=$aflist?>" size="38">
      <font color="#666666">        （多个用半角逗号“,”隔开）</font></td>
    </tr>
    <tr> 
      <td rowspan="2" valign="top" bgcolor="#FFFFFF"><strong>列表模板:</strong><br>
        <br>
        (<font color="#FF0000">
        <input name="doautofile" type="checkbox" id="doautofile" value="1">
自动生成更新文件<br>
      </font>选择后修改文件内容无效)</td>
      <td height="25" bgcolor="#FFFFFF"> <textarea name="temptext" cols="70" rows="20" id="temptext" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($temptext))?></textarea></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">模板格式: 列表头[!--empirenews.listtemp--]列表内容[!--empirenews.listtemp--]列表尾<br>
      字段调用用：&lt;?=$r['字段名']?&gt;</td>
    </tr>
    <tr>
      <td height="25" valign="top" bgcolor="#FFFFFF"><strong>直接修改文件内容:</strong></td>
      <td height="25" bgcolor="#FFFFFF">请将文件内容<a href="#ecms" onClick="window.clipboardData.setData('Text',document.modlistform.filetext.value);document.modlistform.filetext.select()" title="点击复制文件内容"><strong>复制到Dreamweaver编辑(推荐)</strong></a></td>
    </tr>
    <tr>
      <td height="25" colspan="2" valign="top" bgcolor="#FFFFFF"><textarea name="filetext" cols="90" rows="27" id="filetext" wrap="OFF" style="WIDTH: 100%"><?=ehtmlspecialchars($filetext)?>
      </textarea></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="修改"> 
         <input type="reset" name="Submit2" value="重置"> <input name="lid" type="hidden" id="lid" value="<?=$lid?>"> 
      <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
      <input name="ecms" type="hidden" id="ecms" value="<?=$modlistedit?>"></td>
    </tr>
	</form>
</table>
  <?php
  }
  ?>
<?php
}
else
{
?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="modlistform" method="post" action="../ecmsmod.php" onSubmit="return confirm('确认要增加？');">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr> 
      <td height="25" colspan="2" class="header"><?=$word?></td>
    </tr>
    <tr> 
      <td width="23%" height="25" bgcolor="#FFFFFF">列表名称:</td>
      <td width="77%" height="25" bgcolor="#FFFFFF"> <input name="lname" type="text" id="lname" value="<?=$r['lname']?>" size="38">
      *</td>
    </tr>
    <tr> 
      <td height="25" valign="top" bgcolor="#FFFFFF">列表说明:</td>
      <td height="25" bgcolor="#FFFFFF"> <textarea name="lsay" cols="70" rows="8" id="lsay"><?=stripSlashes($r['lsay'])?></textarea></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="增加"> 
         <input type="reset" name="Submit2" value="重置"> <input name="lid" type="hidden" id="lid" value="<?=$lid?>"> 
      <input name="enews" type="hidden" id="enews" value="<?=$enews?>"></td>
    </tr>
	</form>
</table>
<?php
}
?>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>