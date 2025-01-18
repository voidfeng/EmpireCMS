<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require "../../../e/data/".LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"fileclass");

//增加附件分类
function AddFileClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$cname=hRepPostStr($add['cname'],1,1);
	$cclassid=(int)$add['classid'];
	$myorder=(int)$add['myorder'];
	$doing=(int)$add['doing'];
	if(!$cname)
	{printerror("EmptyFileClass","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"fileclass");
	if($doing==1)
	{
		$tb=$dbtbpre.'enewsfileclasst';
		$addname='2';
	}
	else
	{
		$tb=$dbtbpre.'enewsfileclass';
		$addname='';
	}
	$sql=$empire->updatesql("insert into ".$tb."(cname,classid,myorder) values('$cname','$cclassid','$myorder');","ins");
	$cid=$empire->lastid($tb,'cid');
	if($sql)
	{
		//操作日志
		insert_dolog("cid=".$cid."&doing=".$doing."<br>cname=".$cname);
		printerror("AddFileClassSuccess","AddFileClass.php?enews=AddFileClass&doing=".$doing.hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改附件分类
function EditFileClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$add['cid'];
	$cname=hRepPostStr($add['cname'],1,1);
	$cclassid=(int)$add['classid'];
	$myorder=(int)$add['myorder'];
	$doing=(int)$add['doing'];
	if(!$cid||!$cname)
	{printerror("EmptyFileClass","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"fileclass");
	if($doing==1)
	{
		$tb=$dbtbpre.'enewsfileclasst';
		$addname='2';
	}
	else
	{
		$tb=$dbtbpre.'enewsfileclass';
		$addname='';
	}
	$sql=$empire->query("update ".$tb." set cname='$cname',classid='$cclassid',myorder='$myorder' where cid='$cid'");
	if($sql)
	{
		//操作日志
		insert_dolog("cid=".$cid."&doing=".$doing."<br>cname=".$cname);
		printerror("EditFileClassSuccess","ListFileClass.php?doing=".$doing.hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除附件分类
function DelFileClass($add,$userid,$username){
	global $empire,$dbtbpre;
	$cid=(int)$add['cid'];
	$doing=(int)$add['doing'];
	if(!$cid)
	{printerror("NotDelFileClassid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"fileclass");
	if($doing==1)
	{
		$tb=$dbtbpre.'enewsfileclasst';
		$addname='2';
	}
	else
	{
		$tb=$dbtbpre.'enewsfileclass';
		$addname='';
	}
	
	$r=$empire->fetch1("select cid,cname from ".$tb." where cid='$cid'");
	if(!$r['cid'])
	{
		printerror("NotDelFileClassid","history.go(-1)");
	}
	$sql=$empire->query("delete from ".$tb." where cid='$cid'");
	if($sql)
	{
		//操作日志
		insert_dolog("cid=".$cid."&doing=".$doing."<br>cname=".$r['cname']);
		printerror("DelFileClassSuccess","ListFileClass.php?doing=".$doing.hReturnEcmsHashStrHref2(0));
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

//增加
if($enews=="AddFileClass")
{
	AddFileClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditFileClass")//修改
{
	EditFileClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelFileClass")//删除
{
	DelFileClass($_GET,$logininid,$loginin);
}


$doing=(int)$_GET['doing'];
if($doing==1)
{
	$tb=$dbtbpre.'enewsfileclasst';
	$addname='2';
	$addno='2';
}
else
{
	$tb=$dbtbpre.'enewsfileclass';
	$addname='1';
	$addno='';
}
$search="&doing=$doing".$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from ".$tb;
$totalquery="select count(*) as total from ".$tb;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by cid desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=ListFileClass.php?doing=$doing".$ecms_hashur['ehref'].">管理附件分类".$addname."</a>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理附件分类<?=$addname?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr> 
    <td width="50%" height="32">位置: 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit5" value="增加附件分类<?=$addname?>" onclick="self.location.href='AddFileClass.php?enews=AddFileClass&doing=<?=$doing?><?=$ecms_hashur['ehref']?>';">
      </div></td>
  </tr>
</table>
<br>
<table width="800" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="12%" height="25"><div align="center">ID</div></td>
    <td width="66%" height="25"><div align="center">分类名</div></td>
    <td width="22%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('DelFileClass',1);
  
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"><?=$r['cid']?></div></td>
    <td height="25"><a href="ListFile.php?type=9&modtype=1&cid<?=$addno?>=<?=$r['cid']?><?=$ecms_hashur['ehref']?>" target="_blank"><?=$r['cname']?></a></td>
    <td height="25"><div align="center">[<a href="AddFileClass.php?enews=EditFileClass&cid=<?=$r['cid']?>&doing=<?=$doing?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="ListFileClass.php?enews=DelFileClass&cid=<?=$r['cid']?>&doing=<?=$doing?><?=$ecms_hashur['href'].$efh?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
  </tr>
  <?php
  }
  ?>
  <tr bgcolor="#FFFFFF">
    <td height="25" colspan="3">&nbsp;<?=$returnpage?></td>
  </tr>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>