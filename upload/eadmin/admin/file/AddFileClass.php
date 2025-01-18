<?php
define('EmpireCMSAdmin','1');
require('../../../e/class/connect.php');
require('../../../e/class/functions.php');
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"fileclass");

$enews=ehtmlspecialchars($_GET['enews']);
$cid=(int)$_GET['cid'];
$doing=(int)$_GET['doing'];
eCheckStrType(4,$enews,1);

if($doing==1)
{
	$tb=$dbtbpre.'enewsfileclasst';
	$addname='2';
}
else
{
	$tb=$dbtbpre.'enewsfileclass';
	$addname='1';
}

if($enews=='EditFileClass')
{
	$enews='EditFileClass';
}
else
{
	$enews='AddFileClass';
}
//formhash
$efh=heformhash_get($enews);

$postword='增加附件分类'.$addname;
$url="<a href=ListFileClass.php?doing=$doing".$ecms_hashur['ehref'].">管理附件分类".$addname."</a>&nbsp;&gt;&nbsp;".$postword;
//初使化数据
$r=array();
$r['myorder']=0;

//复制
$docopy=RepPostStr($_GET['docopy'],1);
$copyclass=0;
if($docopy&&$enews=="AddFileClass")
{
	$copyclass=1;
}
$ecmsfirstpost=1;
//修改
if($enews=="EditFileClass"||$copyclass)
{
	$ecmsfirstpost=0;
	if($copyclass)
	{
		$thisdo="复制";
	}
	else
	{
		$thisdo="修改";
	}
	$cid=(int)$_GET['cid'];
	$r=$empire->fetch1("select * from ".$tb." where cid='$cid'");
	$url="<a href=ListFileClass.php?doing=$doing".$ecms_hashur['ehref'].">管理附件分类".$addname."</a>&nbsp;&gt;&nbsp;".$thisdo."附件分类".$addname."：".$r['cname'];
	$postword=$thisdo.'附件分类'.$addname;
	//复制分类
	if($copyclass)
	{
		$r['cname'].='(1)';
	}
}

//栏目
$options=ShowClass_AddClass("",$r['classid'],0,"|-",0,0);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$postword?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?>  
    </td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" id="form1" method="post" action="ListFileClass.php">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"> 
        <?=$postword?>
        <input type=hidden name=enews value=<?=$enews?>>
		<input name="doing" type="hidden" id="doing" value="<?=$doing?>">
		<input name="cid" type="hidden" id="cid" value="<?=$cid?>">		</td>
    </tr>
    
    <tr> 
      <td width="24%" height="25" bgcolor="#FFFFFF">分类名称(*)</td>
      <td width="76%" height="25" bgcolor="#FFFFFF"> <input name="cname" type="text" id="cname" value="<?=$r['cname']?>" size="38">         </td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">所属栏目</td>
      <td height="25" bgcolor="#FFFFFF"><select name="classid" id="classid">
          <option value="0">隶属于所有栏目</option>
          <?=$options?>
        </select> <input type="button" name="Submit622232" value="管理栏目" onclick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>');"> 
        <font color="#666666">(选择父栏目，将应用于子栏目)</font>
      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">排序</td>
      <td bgcolor="#FFFFFF"><input name="myorder" type="text" id="myorder" value="<?=$r['myorder']?>" size="38"> 
        <font color="#666666"> (值越小越前面)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"></div></td>
      <td bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="提交"> &nbsp;&nbsp; 
        <input type="reset" name="Submit2" value="重置"> </td>
    </tr>
  </form>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>