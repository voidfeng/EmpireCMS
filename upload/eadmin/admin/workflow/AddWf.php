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
CheckLevel($logininid,$loginin,$classid,"workflow");
$enews=ehtmlspecialchars($_GET['enews']);
$postword='增加工作流';
$r['myorder']=0;
$url="<a href=ListWf.php".$ecms_hashur['whehref'].">管理工作流</a> &gt; 增加工作流";
eCheckStrType(4,$enews,1);
if($enews=='EditWorkflow')
{
	$enews='EditWorkflow';
}
else
{
	$enews='AddWorkflow';
}
//formhash
$efh=heformhash_get($enews);

//修改
if($enews=="EditWorkflow")
{
	$postword='修改工作流';
	$wfid=(int)$_GET['wfid'];
	$r=$empire->fetch1("select wfid,wfname,wftext,myorder,canedit from {$dbtbpre}enewsworkflow where wfid='$wfid'");
	$url="<a href=ListWf.php".$ecms_hashur['whehref'].">管理工作流</a> -&gt; 修改工作流：<b>".$r['wfname']."</b>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>工作流</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?></td>
  </tr>
</table>
<form name="form1" method="post" action="ListWf.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"> 
        <?=$postword?>
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="wfid" type="hidden" id="wfid" value="<?=$wfid?>">      </td>
    </tr>
    <tr> 
      <td width="20%" height="25" bgcolor="#FFFFFF">工作流名称</td>
      <td width="80%" height="25" bgcolor="#FFFFFF"> <input name="wfname" type="text" id="wfname" value="<?=$r['wfname']?>" size="42">      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">工作流描述</td>
      <td height="25" bgcolor="#FFFFFF"> <textarea name="wftext" cols="60" rows="5" id="wftext"><?=ehtmlspecialchars($r['wftext'])?></textarea></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">流转中的信息可修改</td>
      <td height="25" bgcolor="#FFFFFF"><input type="radio" name="canedit" value="1"<?=$r['canedit']==1?' checked':''?>>可以修改
          <input type="radio" name="canedit" value="0"<?=$r['canedit']==0?' checked':''?>>不能修改</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">排序</td>
      <td height="25" bgcolor="#FFFFFF"><font color="#666666">
        <input name="myorder" type="text" id="myorder" value="<?=$r['myorder']?>" size="42">
        (值越小显示越前面)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="提交"> 
        <input type="reset" name="Submit2" value="重置"></td>
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