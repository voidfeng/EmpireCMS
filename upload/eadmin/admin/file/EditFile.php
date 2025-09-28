<?php
define('EmpireCMSAdmin','1');
require('../../../e/class/connect.php');
require('../../../e/class/functions.php');
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
//CheckLevel($logininid,$loginin,$classid,"file");

$modtype=(int)$_GET['modtype'];
$fstb=(int)$_GET['fstb'];
$fileid=(int)$_GET['fileid'];
$enews=$_GET['enews'];
if($enews=='TEditFileOne')
{
	$enews='TEditFileOne';
}
else
{
	$enews='EditFileOne';
}
//formhash
$efh=heformhash_get($enews);

$url="修改附件";

//修改
$r=$empire->fetch1("select * from ".eReturnFileTable($modtype,$fstb)." where fileid='$fileid'");
if(!$r['fileid'])
{
	printerror("ErrorUrl","history.go(-1)");
}
//验证权限
if($enews=='TEditFileOne')
{
	$pageckur=$empire->fetch1("select groupid,adminclass,filelevel from {$dbtbpre}enewsuser where userid='$logininid'".do_dblimit_one());
	if($pageckur['filelevel'])
	{
		$pageckgr=$empire->fetch1("select dofile from {$dbtbpre}enewsgroup where groupid='".$pageckur['groupid']."'");
		if(!$pageckgr['dofile'])
		{
			$classid=(int)$r['classid'];
			if(!$class_r[$classid]['classid'])
			{
				printerror("NotLevel","history.go(-1)");
			}
			if(!strstr($pageckur['adminclass'],'|'.$classid.'|'))
			{
				printerror("NotLevel","history.go(-1)");
			}
		}
	}
	else
	{
		CheckLevel($logininid,$loginin,$classid,"file");
	}
}
else
{
	CheckLevel($logininid,$loginin,$classid,"file");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>修改附件</title>
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
  <form name="editfileform" id="editfileform" method="post" action="../ecmsfile.php">
    <?=$ecms_hashur['form']?>
    <?php echo $efh; ?>
    <input type=hidden name=modtype value="<?=$modtype?>">
    <input type=hidden name=fstb value="<?=$fstb?>">
    <tr class="header"> 
      <td height="25" colspan="2"> 
        修改附件
        <input type=hidden name=enews value=<?=$enews?>> 
        <input name="fileid" type="hidden" id="fileid" value="<?=$r['fileid']?>"> </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">文件ID</td>
      <td height="25" bgcolor="#FFFFFF"><b>
        <?=$r['fileid']?>
        </b></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">文件名</td>
      <td height="25" bgcolor="#FFFFFF"><b>
        <?=$r['filename']?>
        </b></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">原别名</td>
      <td height="25" bgcolor="#FFFFFF"><b><?=$r['no']?></b></td>
    </tr>
    <tr> 
      <td width="24%" height="25" bgcolor="#FFFFFF">新别名</td>
      <td width="76%" height="25" bgcolor="#FFFFFF"> <input name="fileno" type="text" id="fileno" value="<?=$r['no']?>" size="38"> 
      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">所属附件分类1</td>
      <td height="25" bgcolor="#FFFFFF"><select name="cid" id="cid">
          <option value="0">不归类</option>
		  <?=PubReturnSelectClass('enewsfileclass','cid','cname','',$r['cid'],'myorder asc','')?>
        </select>
        <input type="button" name="Submit622232" value="管理附件分类1" onclick="window.open('ListFileClass.php?doing=0<?=$ecms_hashur['ehref']?>');"></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">所属附件分类2</td>
      <td bgcolor="#FFFFFF"><select name="cid2" id="cid2">
          <option value="0">不归类</option>
		  <?=PubReturnSelectClass('enewsfileclasst','cid','cname','',$r['cid2'],'myorder asc','')?>
        </select>
        <input type="button" name="Submit6222322" value="管理附件分类2" onclick="window.open('ListFileClass.php?doing=1<?=$ecms_hashur['ehref']?>');"></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"></div></td>
      <td bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="修改"> &nbsp;&nbsp; 
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