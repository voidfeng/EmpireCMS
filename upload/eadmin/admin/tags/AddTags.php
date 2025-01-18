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
CheckLevel($logininid,$loginin,$classid,"tags");
$enews=ehtmlspecialchars($_GET['enews']);
$postword='增加TAGS';
$url="<a href=ListTags.php".$ecms_hashur['whehref'].">管理TAGS</a> &gt; 增加TAGS";
$fcid=(int)$_GET['fcid'];
eCheckStrType(4,$enews,1);
if($enews=='EditTags')
{
	$enews='EditTags';
}
else
{
	$enews='AddTags';
}
//formhash
$efh=heformhash_get($enews);
//修改
if($enews=="EditTags")
{
	$postword='修改TAGS';
	$tagid=(int)$_GET['tagid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewstags where tagid='$tagid'");
	$url="<a href=ListTags.php".$ecms_hashur['whehref'].">管理TAGS</a> -&gt; 修改TAGS：<b>".$r['tagname']."</b>";
}
//栏目
$options=ShowClass_AddClass("",$r['classid'],0,"|-",0,0);
//分类
$cs='';
$csql=$empire->query("select classid,classname from {$dbtbpre}enewstagsclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($r['cid']==$cr['classid'])
	{
		$select=" selected";
	}
	$cs.="<option value='".$cr['classid']."'".$select.">".$cr['classname']."</option>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>TAGS</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?></td>
  </tr>
</table>
<form name="form1" method="post" action="ListTags.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"><?=$postword?> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tagid" type="hidden" id="tagid" value="<?=$tagid?>">
        <input name="fcid" type="hidden" id="fcid" value="<?=$fcid?>"> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="18%" height="25">TAG名称:</td>
      <td width="82%" height="25"> <input name="tagname" type="text" id="tagname" value="<?=$r['tagname']?>" size="42">
        <font color="#666666">(最多20个字)</font> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">所属分类:</td>
      <td height="25"><select name="cid" id="cid">
          <option value="0">不分类</option>
		  <?=$cs?>
        </select> 
        <input type="button" name="Submit62223" value="管理分类" onClick="window.open('TagsClass.php<?=$ecms_hashur['whehref']?>');"></td>
    </tr>
	<tr> 
      <td height="25" bgcolor="#FFFFFF">隶属信息栏目</td>
      <td height="25" bgcolor="#FFFFFF"> <select name="classid" id="classid">
          <option value="0">隶属于所有栏目</option>
          <?=$options?>
        </select> <input type="button" name="Submit622232" value="管理栏目" onClick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>');"> 
        <font color="#666666">(选择父栏目，将应用于子栏目)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">网页标题:</td>
      <td height="25"><input name="tagtitle" type="text" id="tagtitle" value="<?=ehtmlspecialchars($r['tagtitle'])?>" size="42">
      <font color="#666666">(最多60个字)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">网页关键词:</td>
      <td height="25"><input name="tagkey" type="text" id="tagkey" value="<?=ehtmlspecialchars($r['tagkey'])?>" size="42">
      <font color="#666666">(最多100个字)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">网页描述:</td>
      <td height="25"><input name="tagdes" type="text" id="tagdes" value="<?=ehtmlspecialchars($r['tagdes'])?>" size="42">
      <font color="#666666">(最多255个字)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"> <input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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