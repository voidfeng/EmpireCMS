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

//返回用户组
function ReturnWfGroup($groupid){
	$groupid=eCheckEmptyArray($groupid);
	$count=count($groupid);
	if($count==0)
	{
		return '';
	}
	$ids=',';
	for($i=0;$i<$count;$i++)
	{
		$ids.=$groupid[$i].',';
	}
	return $ids;
}

//增加节点
function AddWorkflowItem($add,$userid,$username){
	global $empire,$dbtbpre;
	$wfid=(int)$add['wfid'];
	$tno=(int)$add['tno'];
	$lztype=(int)$add['lztype'];
	$tbdo=(int)$add['tbdo'];
	$tddo=(int)$add['tddo'];
	if(!$wfid||!$tno)
	{
		printerror('EmptyWorkflowItem','history.go(-1)');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"workflow");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsworkflowitem where wfid='$wfid' and tno='$tno'".do_dblimit_cone());
	if($num)
	{
		printerror('HaveWorkflowItem','history.go(-1)');
	}
	$groupid=ReturnWfGroup($add['groupid']);
	$userclass=ReturnWfGroup($add['userclass']);
	$add['username']=trim($add['username']);
	if($add['username'])
	{
		$usernames=','.$add['username'].',';
	}
	else
	{
		$usernames='';
	}
	if($groupid==''&&$userclass==''&&$add['username']=='')
	{
		printerror('EmptyWorkflowItemUser','history.go(-1)');
	}
	$add['tname']=hRepPostStr($add['tname'],1);
	$add['ttext']=hRepPostStr($add['ttext'],1);
	$groupid=hRepPostStr($groupid,1);
	$userclass=hRepPostStr($userclass,1);
	$usernames=hRepPostStr($usernames,1);
	$add['tstatus']=hRepPostStr($add['tstatus'],1);
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsworkflowitem(wfid,tname,tno,ttext,groupid,userclass,username,lztype,tbdo,tddo,tstatus) values('$wfid','".$add['tname']."','$tno','".$add['ttext']."','$groupid','$userclass','$usernames','$lztype','$tbdo','$tddo','".$add['tstatus']."');","ins");
	$tid=$empire->lastid($dbtbpre.'enewsworkflowitem','tid');
	if($sql)
	{
		//操作日志
		insert_dolog("wfid=$wfid&tid=$tid<br>tname=".$add['tname']);
		printerror("AddWorkflowItemSuccess","AddWfItem.php?enews=AddWorkflowItem&wfid=$wfid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改节点
function EditWorkflowItem($add,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$add['tid'];
	$wfid=(int)$add['wfid'];
	$tno=(int)$add['tno'];
	$lztype=(int)$add['lztype'];
	$tbdo=(int)$add['tbdo'];
	$tddo=(int)$add['tddo'];
	if(!$tid||!$wfid||!$tno)
	{
		printerror('EmptyWorkflowItem','history.go(-1)');
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"workflow");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsworkflowitem where wfid='$wfid' and tno='$tno' and tid<>$tid".do_dblimit_cone());
	if($num)
	{
		printerror('HaveWorkflowItem','history.go(-1)');
	}
	$groupid=ReturnWfGroup($add['groupid']);
	$userclass=ReturnWfGroup($add['userclass']);
	$add['username']=trim($add['username']);
	if($add['username'])
	{
		$usernames=','.$add['username'].',';
	}
	else
	{
		$usernames='';
	}
	if($groupid==''&&$userclass==''&&$add['username']=='')
	{
		printerror('EmptyWorkflowItemUser','history.go(-1)');
	}
	$add['tname']=hRepPostStr($add['tname'],1);
	$add['ttext']=hRepPostStr($add['ttext'],1);
	$groupid=hRepPostStr($groupid,1);
	$userclass=hRepPostStr($userclass,1);
	$usernames=hRepPostStr($usernames,1);
	$add['tstatus']=hRepPostStr($add['tstatus'],1);
	$sql=$empire->query("update {$dbtbpre}enewsworkflowitem set tname='".$add['tname']."',tno='$tno',ttext='".$add['ttext']."',groupid='$groupid',userclass='$userclass',username='$usernames',lztype='$lztype',tbdo='$tbdo',tddo='$tddo',tstatus='".$add['tstatus']."' where tid='$tid'");
	if($sql)
	{
		//操作日志
		insert_dolog("wfid=$wfid&tid=$tid<br>tname=".$add['tname']);
		printerror("EditWorkflowItemSuccess","ListWfItem.php?wfid=$wfid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除节点
function DelWorkflowItem($add,$userid,$username){
	global $empire,$dbtbpre;
	$tid=(int)$add['tid'];
	$wfid=(int)$add['wfid'];
	if(!$tid||!$wfid)
	{
		printerror("NotDelWorkflowItemid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"workflow");
	$r=$empire->fetch1("select tname from {$dbtbpre}enewsworkflowitem where tid='$tid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsworkflowitem where tid='$tid'");
	if($sql)
	{
		//操作日志
		insert_dolog("wfid=$wfid&tid=$tid<br>tname=".$r['tname']);
		printerror("DelWorkflowItemSuccess","ListWfItem.php?wfid=$wfid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改节点编号
function EditWorkflowItemTno($add,$userid,$username){
	global $empire,$dbtbpre;
	$wfid=(int)$add['wfid'];
	$tno=$add['tno'];
	$tid=$add['tid'];
	$tid=eCheckEmptyArray($tid);
	for($i=0;$i<count($tid);$i++)
	{
		$newtno=(int)$tno[$i];
		if(empty($newtno))
		{
			continue;
		}
		$newtid=(int)$tid[$i];
		$empire->query("update {$dbtbpre}enewsworkflowitem set tno='$newtno' where tid='$newtid'");
    }
	//操作日志
	insert_dolog("wfid=$wfid");
	printerror("EditWorkflowItemSuccess","ListWfItem.php?wfid=$wfid".hReturnEcmsHashStrHref2(0));
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="AddWorkflowItem")//增加节点
{
	AddWorkflowItem($_POST,$logininid,$loginin);
}
elseif($enews=="EditWorkflowItem")//修改节点
{
	EditWorkflowItem($_POST,$logininid,$loginin);
}
elseif($enews=="DelWorkflowItem")//删除节点
{
	DelWorkflowItem($_GET,$logininid,$loginin);
}
elseif($enews=="EditWorkflowItemTno")//修改节点编号
{
	EditWorkflowItemTno($_POST,$logininid,$loginin);
}

$wfid=(int)$_GET['wfid'];
if(!$wfid)
{
	printerror('ErrorUrl','');
}
$wfr=$empire->fetch1("select wfid,wfname from {$dbtbpre}enewsworkflow where wfid='$wfid'");
if(!$wfr['wfid'])
{
	printerror('ErrorUrl','');
}
$query="select tid,tname,tno,lztype from {$dbtbpre}enewsworkflowitem where wfid='$wfid' order by tno,tid";
$sql=$empire->query($query);
$url="<a href=ListWf.php".$ecms_hashur['whehref'].">管理工作流</a> &gt; ".$wfr['wfname']." &gt; <a href='ListWfItem.php?wfid=$wfid".$ecms_hashur['ehref']."'>管理节点</a>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>工作流</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr> 
    <td width="50%" height="32">位置: 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit5" value="增加节点" onclick="self.location.href='AddWfItem.php?enews=AddWorkflowItem&wfid=<?=$wfid?><?=$ecms_hashur['ehref']?>';">
      </div></td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="ListWfItem.php">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('EditWorkflowItemTno'); ?>
    <tr class="header"> 
      <td width="10%"><div align="center">编号</div></td>
      <td width="44%" height="25"> <div align="center">节点名称</div></td>
      <td width="16%"><div align="center">流转方式</div></td>
      <td width="23%" height="25"><div align="center">操作</div></td>
    </tr>
    <?php
	//formhash
	$efh=heformhash_get('DelWorkflowItem',1);
	
  while($r=$empire->fetch($sql))
  {
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="tno[]" type="text" id="tno[]" value="<?=$r['tno']?>" size="5">
		<input type="hidden" name="tid[]" value="<?=$r['tid']?>">
        </div></td>
      <td height="25"> 
        <?=$r['tname']?>
      </td>
      <td><div align="center"> 
          <?=$r['lztype']==1?'会签':'普通流转'?>
        </div></td>
      <td height="25"><div align="center">[<a href="AddWfItem.php?enews=EditWorkflowItem&tid=<?=$r['tid']?>&wfid=<?=$wfid?><?=$ecms_hashur['ehref']?>">修改</a>] 
          [<a href="AddWfItem.php?enews=AddWorkflowItem&tid=<?=$r['tid']?>&wfid=<?=$wfid?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a>] 
          [<a href="ListWfItem.php?enews=DelWorkflowItem&tid=<?=$r['tid']?>&wfid=<?=$wfid?><?=$ecms_hashur['href'].$efh?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
    </tr>
    <?php
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="4"> <input type="submit" name="Submit" value="修改编号"> 
        <input name="enews" type="hidden" id="enews" value="EditWorkflowItemTno">
        <input name="wfid" type="hidden" id="wfid" value="<?=$wfid?>"> </td>
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