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
CheckLevel($logininid,$loginin,$classid,"dtuserpage");

//处理动态页面字段变量
function DoPostDtUserpageVar($add){
	$edb_ir['aid']=(int)$add['aid'];
	$edb_ir['aname']=hRepPostStr($add['aname'],1);
	$edb_ir['cid']=(int)$add['cid'];
	$edb_ir['atype']=(int)$add['atype'];
	$edb_ir['isopen']=(int)$add['isopen'];
	$edb_ir['avar']=RepPostVar($add['avar']);
	$edb_ir['avarid']=RepPostVar($add['avarid']);
	$edb_ir['apass']=RepPostVar($add['apass']);
	$edb_ir['rtype']=(int)$add['rtype'];
	$edb_ir['actime']=(int)$add['actime'];
	$edb_ir['maxpage']=(int)$add['maxpage'];
	$edb_ir['addcs']=AddAddsData($add['addcs']);
	$edb_ir['atemptext']=RepPhpAspJspcode($add['atemptext']);
	$edb_ir['fcid']=(int)$add['fcid'];
	$edb_ir['rtype']=0;
	return $edb_ir;
}

//增加自定义动态页面
function AddDtUserpage($add,$userid,$username){
	global $empire,$dbtbpre;
	//处理
	$edb_ir=DoPostDtUserpageVar($add);
	if(!$edb_ir['aname'])
	{
		printerror("EmptyDtUserpageName","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"dtuserpage");
	$edb_ir['addtime']=time();
	$edb_ir['edittime']=0;
	$edb_ir['aclast']=$edb_ir['addtime'];
	
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsdtuserpage(aname,cid,atype,isopen,addtime,edittime,onclick,avar,avarid,apass,rtype,actime,aclast,maxpage,addcs,atemptext) values('".$edb_ir['aname']."','".$edb_ir['cid']."','".$edb_ir['atype']."','".$edb_ir['isopen']."','".$edb_ir['addtime']."','".$edb_ir['edittime']."',0,'".$edb_ir['avar']."','".$edb_ir['avarid']."','".$edb_ir['apass']."','".$edb_ir['rtype']."','".$edb_ir['actime']."','".$edb_ir['aclast']."','".$edb_ir['maxpage']."','".$edb_ir['addcs']."','".eaddslashes2($edb_ir['atemptext'])."');","ins");
	$aid=$empire->lastid($dbtbpre.'enewsdtuserpage','aid');
	//生成动态页面文件
	DtNewsBq_dtup($aid,eaddslashes($edb_ir['atemptext']),0);
	if($sql)
	{
		//操作日志
		insert_dolog("aid=$aid&aname=".$edb_ir['aname']);
		printerror("AddDtUserpageSuccess","AddDtUserpage.php?enews=AddDtUserpage&fcid=".$edb_ir['fcid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改自定义动态页面
function EditDtUserpage($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	//处理
	$edb_ir=DoPostDtUserpageVar($add);
	if(!$edb_ir['aid']||!$edb_ir['aname'])
	{
		printerror("EmptyDtUserpageName","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"dtuserpage");
	$edb_ir['edittime']=time();
	$edb_ir['aclast']=$edb_ir['edittime'];
	
	$sql=$empire->query("update {$dbtbpre}enewsdtuserpage set aname='".$edb_ir['aname']."',cid='".$edb_ir['cid']."',atype='".$edb_ir['atype']."',isopen='".$edb_ir['isopen']."',edittime='".$edb_ir['edittime']."',avar='".$edb_ir['avar']."',avarid='".$edb_ir['avarid']."',apass='".$edb_ir['apass']."',rtype='".$edb_ir['rtype']."',actime='".$edb_ir['actime']."',aclast='".$edb_ir['aclast']."',maxpage='".$edb_ir['maxpage']."',addcs='".$edb_ir['addcs']."',atemptext='".eaddslashes2($edb_ir['atemptext'])."' where aid='".$edb_ir['aid']."'");
	//生成动态页面文件
	DtNewsBq_dtup($edb_ir['aid'],eaddslashes($edb_ir['atemptext']),0);
	if($sql)
	{
		//操作日志
		insert_dolog("aid=".$edb_ir['aid']."&aname=".$edb_ir['aname']);
		printerror("EditDtUserpageSuccess","ListDtUserpage.php?cid=".$edb_ir['fcid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除自定义动态页面
function DelDtUserpage($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$edb_ir['aid']=(int)$add['aid'];
	$edb_ir['fcid']=(int)$add['fcid'];
	if(empty($edb_ir['aid']))
	{
		printerror("NotChangeDtUserpageid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"dtuserpage");
	$r=$empire->fetch1("select aid,aname from {$dbtbpre}enewsdtuserpage where aid='".$edb_ir['aid']."'");
	if(!$r['aid'])
	{
		printerror("NotChangeDtUserpageid","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsdtuserpage where aid='".$edb_ir['aid']."'");
	//删除动态页面文件
	$dtupfile=ECMS_PATH.'c/ecacheapi/edtuserpage/dt'.$r['aid'].'.php';
	DelFiletext($dtupfile);
	if($sql)
	{
		//操作日志
		insert_dolog("aid=".$r['aid']."&aname=".$r['aname']);
		printerror("DelDtUserpageSuccess","ListDtUserpage.php?cid=".$edb_ir['fcid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}


//操作
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//增加自定义动态页面
if($enews=="AddDtUserpage")
{
	AddDtUserpage($_POST,$logininid,$loginin);
}
//修改自定义动态页面
elseif($enews=="EditDtUserpage")
{
	EditDtUserpage($_POST,$logininid,$loginin);
}
//删除自定义动态页面
elseif($enews=="DelDtUserpage")
{
	DelDtUserpage($_GET,$logininid,$loginin);
}

$url="";
$add="";
$search="";
$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量

//分类
$cid=(int)$_GET['cid'];
if($cid)
{
	$add.=" and cid='$cid'";
	$search.="&cid=$cid";
}
//搜索
$keyboard='';
if($_GET['sear'])
{
	$search.="&sear=1";
	//关键字
	$keyboard=RepPostVar2($_GET['keyboard']);
	if($keyboard)
	{
		$show=RepPostStr($_GET['show'],1);
		if($show==1)//名称
		{
			$add.=" and (aname like '%$keyboard%')";
		}
		elseif($show==2)//ID
		{
			$add.=" and (aid='$keyboard')";
		}
		elseif($show==3)//标识
		{
			$add.=" and (avar like '%$keyboard%')";
		}
		elseif($show==4)//标识ID
		{
			$add.=" and (avarid like '%$keyboard%')";
		}
		else
		{
			$add.=" and (aid='$keyboard' or aname like '%$keyboard%' or avar like '%$keyboard%' or avarid like '%$keyboard%')";
		}
		$search.="&keyboard=$keyboard&show=$show";
	}
}
if($add)
{
	$add=" where".substr($add,4,strlen($add));
}

$query="select aid,aname,cid,avar,avarid,apass,isopen,atype from {$dbtbpre}enewsdtuserpage".$add;
$totalquery="select count(*) as total from {$dbtbpre}enewsdtuserpage".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by aid desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);

//分类
$cstr="";
$csql=$empire->query("select cid,cname from {$dbtbpre}enewsdtuserpageclass order by myorder,cid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr['cid']==$cid)
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr['cid']."'".$select.">".$cr['cname']."</option>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理自定义动态页面</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： <a href="ListDtUserpage.php<?=$ecms_hashur['whehref']?>">管理自定义动态页面</a>
    </td>
    <td><div align="right" class="emenubutton"> 
        <input type="button" name="Submit5" value="管理自定义动态页面分类" onClick="self.location.href='DtUserpageClass.php<?=$ecms_hashur['whehref']?>';">
		&nbsp;&nbsp;
		<input type="button" name="Submit5" value="增加自定义动态页面" onClick="self.location.href='AddDtUserpage.php?enews=AddDtUserpage<?=$ecms_hashur['ehref']?>';">
      </div></td>
  </tr>
</table>
  
<br>

  <table width="100%" border="0" cellpadding="3" cellspacing="1">
  <form name="sform" method="GET" action="ListDtUserpage.php">
    <tr>
      <td><div align="center">搜索：
          <select name="show" id="show">
            <option value="0"<?=$show==0?' selected':''?>>所有</option>
            <option value="1"<?=$show==1?' selected':''?>>页面名称</option>
			<option value="2"<?=$show==2?' selected':''?>>ID</option>
            <option value="3"<?=$show==3?' selected':''?>>页面标识</option>
            <option value="4"<?=$show==4?' selected':''?>>页面标识ID</option>
          </select>
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="cid" id="cid">
            <option value="0">不限分类</option>
			<?=$cstr?>
          </select>
          <input type="submit" name="Submit" value="显示">
          <input name="sear" type="hidden" id="sear" value="1">
      </div></td>
    </tr>
	</form>
  </table>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="5%" height="25"><div align="center">ID</div></td>
    <td width="42%" height="25"><div align="center">页面名称</div></td>
    <td width="24%"><div align="center">所属分类</div></td>
    <td width="8%"><div align="center">启用</div></td>
    <td width="21%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('DelDtUserpage',1);
  
  while($r=$empire->fetch($sql))
  {
	  $pageurl='../../../e/eapi/DtUserpage.php?aid='.$r['aid'];
	  if($r['apass'])
	  {
		  $pageurl.='&apass='.$r['apass'];
	  }
	  //分类
	  $listcr=$empire->fetch1("select cname from {$dbtbpre}enewsdtuserpageclass where cid='".$r['cid']."'");
  ?>
  <tr bgcolor="#ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r['aid']?>
      </div></td>
    <td height="25"><div align="center"> 
        <a href="<?=$pageurl?>" target="_blank"><?=$r['aname']?></a>
      </div></td>
    <td><div align="center">
        <a href="ListDtUserpage.php?cid=<?=$r['cid']?>" target="_blank"><?=$listcr['cname']?></a>
	</div></td>
    <td><div align="center"><?=$r['isopen']==1?'<b>开</b>':'关'?></div></td>
    <td height="25"><div align="center"> [<a href="AddDtUserpage.php?enews=EditDtUserpage&aid=<?=$r['aid']?>&fcid=<?=$cid?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddDtUserpage.php?enews=AddDtUserpage&docopy=1&aid=<?=$r['aid']?>&fcid=<?=$cid?><?=$ecms_hashur['ehref']?>">复制</a>]  
        [<a href="ListDtUserpage.php?enews=DelDtUserpage&aid=<?=$r['aid']?>&fcid=<?=$cid?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?php
  }
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25" colspan="5">&nbsp; 
      <?=$returnpage?>    </td>
  </tr>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>