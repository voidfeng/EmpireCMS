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

//更新信息数
function UpTagsNum($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$fun_r;
	$line=(int)$add['line'];
	if(empty($line))
	{
		$line=350;
	}
	$startid=(int)$add['startid'];
	$endid=(int)$add['endid'];
	$start=(int)$add['start'];
	//按ID刷新
	$where='';
	if($endid)
	{
		$where.=" and tagid>=$startid and tagid<=$endid";
	}
	$b=0;
	$sql=$empire->query("select tagid,num,cnum from {$dbtbpre}enewstags where tagid>$start".$where." order by tagid".do_dblimit($line));
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r['tagid'];
		$tnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstagsdata where tagid='".$r['tagid']."'");
		$tcnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstagsdata_check where tagid='".$r['tagid']."'");
		if($r['num']!=$tnum||$r['cnum']!=$tcnum)
		{
			$empire->query("update {$dbtbpre}enewstags set num='$tnum',cnum='$tcnum' where tagid='".$r['tagid']."'");
		}
	}
	if(empty($b))
	{
			//操作日志
			insert_dolog("");
			printerror('UpTagsNumSuccess','UpTagsNum.php'.hReturnEcmsHashStrHref2(1));
	}
	echo"<meta http-equiv=\"refresh\" content=\"0;url=UpTagsNum.php?enews=UpTagsNum&startid=$startid&endid=$endid&line=$line&start=$newstart".hReturnEcmsHashStrHref(0).heformhash_get('UpTagsNum',1)."\">".$fun_r['OneUpTagsNumSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)";
	exit();
}

$enews=$_GET['enews'];
if($enews=='UpTagsNum')
{
	hCheckEcmsRHash();
	include("../../../e/data/dbcache/class.php");
	include "../../../e/data/".LoadLang("pub/fun.php");
	UpTagsNum($_GET,$logininid,$loginin);
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
    <td height="32">位置：<a href=ListTags.php<?=$ecms_hashur['whehref']?>>管理TAGS</a> &gt; 批量更新TAGS信息数</td>
  </tr>
</table>
<form name="uptagsnum" method="get" action="UpTagsNum.php" onsubmit="return confirm('确认要操作?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('UpTagsNum'); ?>
    <tr class="header"> 
      <td height="25" colspan="2">批量更新TAGS信息数 
        <input name=enews type=hidden value=UpTagsNum></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">更新TAGS的id范围：</td>
      <td height="25">从
        <input name="startid" type="text" value="0" size="6">
到
<input name="endid" type="text" value="0" size="6">
之间的TAGS <font color="#666666">(两个值为0将更新所有TAGS)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">每组更新数：</td>
      <td width="81%" height="25"><input name="line" type="text" id="line" value="350">      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="开始更新"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" colspan="2"><font color="#666666">说明：当TAGS表里的信息数与实际信息数不符时使用。</font></td>
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