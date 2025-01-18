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
CheckLevel($logininid,$loginin,$classid,"searchurl");

//增加搜索转发
function AddSearchUrl($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$title=hRepPostStr($add['title'],1,1);
	$url=eDoRepPostComStr($add['url'],1);
	if(!$title||!$url)
	{
		printerror("EmptySearchUrl","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"searchurl");
	//重复
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewssearchurl where title='$title'".do_dblimit_cone());
	if($num)
	{
		printerror("HaveSearchUrl","history.go(-1)");
	}
	
	$sql=$empire->updatesql("insert into {$dbtbpre}enewssearchurl(title,url,onclick,lasttime) values('".addslashes($title)."','".addslashes($url)."','0','0');","ins");
	$id=$empire->lastid($dbtbpre.'enewssearchurl','id');
	if(!$public_r['opensearchurl'])
	{
		GetConfig();//更新缓存
	}
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id&title=".$title);
		printerror("AddSearchUrlSuccess","AddSearchUrl.php?enews=AddSearchUrl".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改搜索转发
function EditSearchUrl($add,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$add['id'];
	$title=hRepPostStr($add['title'],1,1);
	$url=eDoRepPostComStr($add['url'],1);
	if(!$id||!$title||!$url)
	{
		printerror("EmptySearchUrl","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"searchurl");
	//重复
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewssearchurl where title='$title' and id<>".$id."".do_dblimit_cone());
	if($num)
	{
		printerror("HaveSearchUrl","history.go(-1)");
	}
	
	$sql=$empire->query("update {$dbtbpre}enewssearchurl set title='".addslashes($title)."',url='".addslashes($url)."' where id='".$id."'");
	if($sql)
	{
		//操作日志
	    insert_dolog("id=$id&title=".$title);
		printerror("EditSearchUrlSuccess","ListSearchUrl.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除搜索转发
function DelSearchUrl($id,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$id=(int)$id;
	if(!$id)
	{
		printerror("NotChangeSearchUrlid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"searchurl");
	$r=$empire->fetch1("select id,title from {$dbtbpre}enewssearchurl where id='$id'");
	if(!$r['id'])
	{
		printerror("NotChangeSearchUrlid","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewssearchurl where id='$id'");
	$opensearchurl=$empire->gettotal("select count(*) as total from {$dbtbpre}enewssearchurl");
	if(!$opensearchurl)
	{
		GetConfig();//更新缓存
	}
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id&title=".$r['title']);
		printerror("DelSearchUrlSuccess","ListSearchUrl.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}


$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="AddSearchUrl")
{
	AddSearchUrl($_POST,$logininid,$loginin);
}
elseif($enews=="EditSearchUrl")
{
	EditSearchUrl($_POST,$logininid,$loginin);
}
elseif($enews=="DelSearchUrl")
{
	$id=$_GET['id'];
	DelSearchUrl($id,$logininid,$loginin);
}
else
{}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=20;//每页显示条数
$page_line=20;//每页显示链接数
$offset=$page*$line;//总偏移量
$search='';
$search.=$ecms_hashur['ehref'];
$query="select * from {$dbtbpre}enewssearchurl";
$totalquery="select count(*) as total from {$dbtbpre}enewssearchurl";
//类别
$add="";
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理搜索转发</title>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置：<a href="ListSearchUrl.php<?=$ecms_hashur['whehref']?>">管理搜索转发</a></td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit" value="增加搜索转发" onclick="self.location.href='AddSearchUrl.php?enews=AddSearchUrl<?=$ecms_hashur['ehref']?>';">
	</div></td>
  </tr>
</table>

<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="ListSearchUrl.php">
  <?=$ecms_hashur['form']?>
    <tr class="header">
      <td width="8%" height="25"> <div align="center">ID</div></td>
      <td width="33%" height="25"> <div align="center">搜索词</div></td>
      <td width="30%"><div align="center">转向地址</div></td>
      <td width="10%"><div align="center">访问数</div></td>
      <td width="19%" height="25"> <div align="center">操作</div></td>
    </tr>
    <?php
	//formhash
	$efh=heformhash_get('DelSearchUrl',1);
	
  while($r=$empire->fetch($sql))
  {
  $jspath=$r['url'];
  $eagotourl=eapage_hGetGotoUrl($jspath,'../',$r['id'],0,'eaGoUrlSearchUrl',0);
  $eagotourl_onclick='';
  if($eagotourl!=$jspath)
  {
	  $eagotourl_onclick=' onclick="window.open(\''.$eagotourl.'\');return false;"';
  }
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'">
      <td height="25"> <div align="center"> 
          <?=$r['id']?>
        </div></td>
      <td height="25"> <div align="center"> 
          <a href="<?=$jspath?>"<?=$eagotourl_onclick?> target="_blank"><?=$r['title']?></a>
        </div></td>
      <td><div align="center">
        <input name="textfield" type="text" value="<?=$jspath?>">
      </div></td>
      <td title="最后访问时间：<?=$r['lasttime']?date("Y-m-d H:i:s",$r['lasttime']):'--'?>"><div align="center"><?=$r['onclick']?></div></td>
      <td height="25"> <div align="center">[<a href="AddSearchUrl.php?enews=EditSearchUrl&id=<?=$r['id']?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="AddSearchUrl.php?enews=AddSearchUrl&docopy=1&id=<?=$r['id']?><?=$ecms_hashur['ehref']?>">复制</a>]&nbsp;[<a href="ListSearchUrl.php?enews=DelSearchUrl&id=<?=$r['id']?><?=$ecms_hashur['href'].$efh?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
    </tr>
    <?php
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="5"> 
        <?=$returnpage?>
        &nbsp;&nbsp;&nbsp;        </td>
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
