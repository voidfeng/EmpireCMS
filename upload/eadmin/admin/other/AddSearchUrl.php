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
CheckLevel($logininid,$loginin,$classid,"searchurl");

$enews=ehtmlspecialchars($_GET['enews']);
$id=(int)$_GET['id'];
eCheckStrType(4,$enews,1);

if($enews=='EditSearchUrl')
{
	$enews='EditSearchUrl';
}
else
{
	$enews='AddSearchUrl';
}
//formhash
$efh=heformhash_get($enews);

$postword='增加搜索转发';
$url="".$postword;
//初使化数据
$r=array();

//复制
$docopy=RepPostStr($_GET['docopy'],1);
if($docopy&&$enews=="AddSearchUrl")
{
	$copyclass=1;
}
$ecmsfirstpost=1;
//修改
if($enews=="EditSearchUrl"||$copyclass)
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
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewssearchurl where id='$id'");
	$url="".$thisdo."搜索转发：".$r['title'];
	$postword=$thisdo.'搜索转发';
	//复制
	if($copyclass)
	{
		$r['title'].='(1)';
	}
}

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
    <td height="32">位置：<a href="ListSearchUrl.php<?=$ecms_hashur['whhref']?>">管理搜索转发</a> &gt; <?=$url?> </td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" id="form1" method="post" action="ListSearchUrl.php">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"> 
        <?=$postword?>
        <input type=hidden name=enews value=<?=$enews?>>
		<input name="id" type="hidden" id="id" value="<?=$id?>">		</td>
    </tr>
    <tr> 
      <td height="25" colspan="2"><?=$postword?></td>
    </tr>
    <tr> 
      <td width="16%" height="25" bgcolor="#FFFFFF">搜索词：</td>
      <td width="84%" height="25" bgcolor="#FFFFFF"> <input name="title" type="text" id="title" value="<?=$r['title']?>" size="60">         </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">转向地址：</td>
      <td bgcolor="#FFFFFF"><input name="url" type="text" id="url" value="<?=$r['url']?>" size="60">
      <font color="#666666">      (不填地址将不会转发)</font></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">&nbsp; </td>
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