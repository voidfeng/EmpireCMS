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

//清理多余数据
function ClearTags($start,$line,$checked,$movecheck,$userid,$username){
	global $empire,$dbtbpre,$class_r,$fun_r;
	$line=(int)$line;
	if(empty($line))
	{
		$line=350;
	}
	$checked=(int)$checked;
	$movecheck=(int)$movecheck;
	if($checked==1)
	{
		$tagsnumf='num';
		$tagsdatatb=$dbtbpre.'enewstagsdata';
	}
	else
	{
		$tagsnumf='cnum';
		$tagsdatatb=$dbtbpre.'enewstagsdata_check';
	}
	$start=(int)$start;
	$b=0;
	$sql=$empire->query("select id,classid,tid,tagid,newstime,mid from ".$tagsdatatb." where tid>$start order by tid".do_dblimit($line));
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r['tid'];
		if(empty($class_r[$r['classid']]['tbname']))
		{
			$empire->query("delete from ".$tagsdatatb." where tid='".$r['tid']."'");
			$empire->query("update {$dbtbpre}enewstags set ".$tagsnumf."=".$tagsnumf."-1 where tagid='".$r['tagid']."'");
			continue;
		}
		$index_r=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$class_r[$r['classid']]['tbname']."_index where id='".$r['id']."'".do_dblimit_one());
		if(!$index_r['id'])
		{
			$empire->query("delete from ".$tagsdatatb." where tid='".$r['tid']."'");
			$empire->query("update {$dbtbpre}enewstags set ".$tagsnumf."=".$tagsnumf."-1 where tagid='".$r['tagid']."'");
		}
		else
		{
			//返回表
			$infotb=ReturnInfoMainTbname($class_r[$r['classid']]['tbname'],$index_r['checked']);
			//主表
			$infor=$empire->fetch1("select stb from ".$infotb." where id='".$r['id']."'".do_dblimit_one());
			//返回表信息
			$infodatatb=ReturnInfoDataTbname($class_r[$r['classid']]['tbname'],$index_r['checked'],$infor['stb']);
			//副表
			$finfor=$empire->fetch1("select infotags from ".$infodatatb." where id='".$r['id']."'".do_dblimit_one());
			$tagr=$empire->fetch1("select tagname from {$dbtbpre}enewstags where tagid='".$r['tagid']."'");
			if(!stristr(','.$finfor['infotags'].',',','.$tagr['tagname'].','))
			{
				$empire->query("delete from ".$tagsdatatb." where tid='".$r['tid']."'");
				$empire->query("update {$dbtbpre}enewstags set ".$tagsnumf."=".$tagsnumf."-1 where tagid='".$r['tagid']."'");
			}
			else
			{
				if($index_r['classid']!=$r['classid'])
				{
					$empire->query("update ".$tagsdatatb." set classid='".$index_r['classid']."' where tid='".$r['tid']."'");
				}
				if($movecheck==1)
				{
					if($index_r['checked']!=$checked)
					{
						MoveCheckTagsData($checked,$r['tid'],$r['tagid'],$index_r['classid'],$r['id'],$r['newstime'],$r['mid']);
					}
				}
			}
		}
	}
	if(empty($b))
	{
		if($checked==1)
		{
			//操作日志
			insert_dolog("");
			printerror('ClearTagsSuccess','ClearTags.php'.hReturnEcmsHashStrHref2(1));
		}
		$checked=1;
	}
	echo"<meta http-equiv=\"refresh\" content=\"0;url=ClearTags.php?enews=ClearTags&checked=$checked&movecheck=$movecheck&line=$line&start=$newstart".hReturnEcmsHashStrHref(0).heformhash_get('ClearTags',1)."\">".$fun_r['OneClearTagsSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)";
	exit();
}

$enews=$_GET['enews'];
if($enews=='ClearTags')
{
	hCheckEcmsRHash();
	include("../../../e/data/dbcache/class.php");
	include "../../../e/data/".LoadLang("pub/fun.php");
	ClearTags($_GET['start'],$_GET['line'],$_GET['checked'],$_GET['movecheck'],$logininid,$loginin);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>TAGS</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<a href=ListTags.php<?=$ecms_hashur['whehref']?>>管理TAGS</a> &gt; 清理多余TAGS信息</td>
  </tr>
</table>
<form name="tagsclear" method="get" action="ClearTags.php" onsubmit="return confirm('确认要操作?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('ClearTags'); ?>
    <tr class="header"> 
      <td height="25" colspan="2">清理多余TAGS信息 <input name=enews type=hidden value=ClearTags></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">每组整理数：</td>
      <td width="81%" height="25"><input name="line" type="text" id="line" value="350">      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">自动互转审核信息：</td>
      <td height="25"><input name="movecheck" type="checkbox" id="movecheck" value="1">
      是  （<font color="#666666">检测信息是否审核，并且相互转向对应表）</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="开始处理"> <input type="reset" name="Submit2" value="重置"></td>
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