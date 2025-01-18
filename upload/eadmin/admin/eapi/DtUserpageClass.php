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
CheckLevel($logininid,$loginin,$classid,"dtuserpage");

//增加分类
function AddDtUserpageClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$edb_ir['cname']=hRepPostStr($add['classname'],1);
	$edb_ir['myorder']=(int)$add['myorder'];
	if(!$edb_ir['cname'])
	{
		printerror("EmptyDtUserpageClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"dtuserpage");
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsdtuserpageclass(cname,myorder) values('".$edb_ir['cname']."','".$edb_ir['myorder']."');","ins");
	$lastid=$empire->lastid($dbtbpre.'enewsdtuserpageclass','cid');
	if($sql)
	{
		//操作日志
		insert_dolog("cid=".$lastid."<br>cname=".$edb_ir['cname']);
		printerror("AddDtUserpageClassSuccess","DtUserpageClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改分类
function EditDtUserpageClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$edb_ir['cid']=(int)$add['cid'];
	$edb_ir['cname']=hRepPostStr($add['classname'],1);
	$edb_ir['myorder']=(int)$add['myorder'];
	if(!$edb_ir['cname']||!$edb_ir['cid'])
	{
		printerror("EmptyDtUserpageClass","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"dtuserpage");
	$sql=$empire->query("update {$dbtbpre}enewsdtuserpageclass set cname='".$edb_ir['cname']."',myorder='".$edb_ir['myorder']."' where cid='".$edb_ir['cid']."'");
	if($sql)
	{
		//操作日志
		insert_dolog("cid=".$edb_ir['cid']."<br>cname=".$edb_ir['cname']);
		printerror("EditDtUserpageClassSuccess","DtUserpageClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除分类
function DelDtUserpageClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$edb_ir['cid']=(int)$add['cid'];
	if(!$edb_ir['cid'])
	{
		printerror("NotDelDtUserpageClassid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"dtuserpage");
	$r=$empire->fetch1("select cname from {$dbtbpre}enewsdtuserpageclass where cid='".$edb_ir['cid']."'");
	$sql=$empire->query("delete from {$dbtbpre}enewsdtuserpageclass where cid='".$edb_ir['cid']."'");
	if($sql)
	{
		//操作日志
		insert_dolog("cid=".$edb_ir['cid']."<br>cname=".$r['cname']);
		printerror("DelDtUserpageClassSuccess","DtUserpageClass.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="AddDtUserpageClass")//增加分类
{
	AddDtUserpageClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditDtUserpageClass")//修改分类
{
	EditDtUserpageClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelDtUserpageClass")//删除分类
{
	DelDtUserpageClass($_GET,$logininid,$loginin);
}
else
{}

$sql=$empire->query("select cid,cname,myorder from {$dbtbpre}enewsdtuserpageclass order by myorder,cid desc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32"><p>位置：<a href="ListDtUserpage.php<?=$ecms_hashur['whehref']?>">管理自定义动态页面</a> &gt; <a href="DtUserpageClass.php<?=$ecms_hashur['whehref']?>">管理自定义动态页面分类</a></p>
    </td>
  </tr>
</table>
<form name="form1" method="post" action="DtUserpageClass.php">
  <table width="800" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('AddDtUserpageClass'); ?>
    <tr class="header">
      <td height="25">增加分类: 
        <input name=enews type=hidden id="enews" value=AddDtUserpageClass>
      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> 分类名称: 
        <input name="cname" type="text" id="cname">
        排序:
        <input name="myorder" type="text" id="myorder" size="8"> 
        <font color="#666666">(值越小越前面)</font> 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
<table width="800" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header">
    <td width="13%"><div align="center">ID</div></td>
    <td width="44%" height="25"><div align="center">分类名称</div></td>
    <td width="21%"><div align="center">排序</div></td>
    <td width="22%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('EditDtUserpageClass');
  $efh1=heformhash_get('DelDtUserpageClass',1);
  
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=DtUserpageClass.php>
	  <?=$ecms_hashur['form']?>
	  <?php echo $efh; ?>
    <input type=hidden name=enews value=EditDtUserpageClass>
    <input type=hidden name=cid value=<?=$r['cid']?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
      <td><div align="center"><?=$r['cid']?></div></td>
      <td height="27"> <div align="center">
          <input name="cname" type="text" id="cname" value="<?=$r['cname']?>">
        </div></td>
      <td><div align="center">
        <input name="myorder" type="text" id="myorder" value="<?=$r['myorder']?>" size="8">
      </div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onClick="if(confirm('确认要删除?')){self.location.href='DtUserpageClass.php?enews=DelDtUserpageClass&cid=<?=$r['cid']?><?=$ecms_hashur['href'].$efh1?>';}">
        </div></td>
    </tr>
  </form>
  <?php
  }
  db_close();
  $empire=null;
  ?>
</table>
<br>
</body>
</html>