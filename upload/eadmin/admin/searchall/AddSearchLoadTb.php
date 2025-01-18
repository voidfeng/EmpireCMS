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
CheckLevel($logininid,$loginin,$classid,"searchall");
$url="<a href=ListSearchLoadTb.php".$ecms_hashur['whehref'].">管理全站搜索数据源</a>&nbsp;>&nbsp;增加搜索数据源";
$word='增加全站搜索数据源';
$enews=ehtmlspecialchars($_GET['enews']);
eCheckStrType(4,$enews,1);
if($enews=='EditSearchLoadTb')
{
	$enews='EditSearchLoadTb';
}
else
{
	$enews='AddSearchLoadTb';
}
//formhash
$efh=heformhash_get($enews);

$r['titlefield']='title';
$r['loadnum']='300';
//修改
if($enews=="EditSearchLoadTb")
{
	$lid=(int)$_GET['lid'];
	$r=$empire->fetch1("select lid,tbname,titlefield,infotextfield,smalltextfield,loadnum from {$dbtbpre}enewssearchall_load where lid='$lid'");
	$url="<a href=ListSearchLoadTb.php".$ecms_hashur['whehref'].">管理全站搜索数据源</a>&nbsp;>&nbsp;修改搜索数据源";
	$word='修改全站搜索数据源';
}
//数据表
$table='';
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable where intb=0 order by tid");
while($tr=$empire->fetch($tsql))
{
	if($r['tbname']==$tr['tbname'])
	{$select=" selected";}
	else
	{$select="";}
	$table.="<option value='".$tr['tbname']."'".$select.">".$tr['tname']."</option>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理全站搜索数据源</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?>    </td>
  </tr>
</table>
<form name="form1" method="post" action="ListSearchLoadTb.php">
<input type=hidden name=enews value=<?=$enews?>>
<input type=hidden name=lid value=<?=$lid?>>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"><?=$word?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">导入的数据表：</td>
      <td height="25"><select name="tbname" id="tbname">
	  <?=$table?>
        </select>
        <font color="#666666">(*)</font> 
        <input name="oldtbname" type="hidden" id="oldtbname" value="<?=$r['tbname']?>"> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="18%" height="25">标题字段：</td>
      <td width="82%" height="25"><input name="titlefield" type="text" id="titlefield" value="<?=$r['titlefield']?>">
        <font color="#666666">(*)</font> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">内容字段：</td>
      <td height="25"><input name="infotextfield" type="text" id="infotextfield" value="<?=$r['infotextfield']?>">
        <font color="#666666">(*)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">简介字段：</td>
      <td height="25"><input name="smalltextfield" type="text" id="smalltextfield" value="<?=$r['smalltextfield']?>">
        <font color="#666666">(*)</font> </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">每组导入记录数：</td>
      <td height="25"><input name="loadnum" type="text" id="loadnum" value="<?=$r['loadnum']?>">
        <font color="#666666">(*)</font></td>
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