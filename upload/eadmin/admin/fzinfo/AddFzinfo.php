<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require('../../../e/data/dbcache/class.php');
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
CheckLevel($logininid,$loginin,$classid,"fzinfo");

$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
$enews=ehtmlspecialchars($_GET['enews']);
$pubid=ReturnInfoPubid($classid,$id);
$tbname=$class_r[$classid]['tbname'];
$modid=(int)$class_r[$classid]['modid'];

//父信息
if(!$classid||!$id||!$tbname||!$pubid)
{
	printerror('ErrorUrl','');
}
$index_infor=$empire->fetch1("select id,checked from {$dbtbpre}ecms_".$tbname."_index where id='".$id."'");
if(!$index_infor['id'])
{
	printerror('ErrorUrl','');
}
$finfotb=ReturnInfoMainTbname($tbname,$index_infor['checked']);
$infor=$empire->fetch1("select * from ".$finfotb." where id='".$id."'".do_dblimit_one());
if(!$infor['id']||$infor['classid']!=$classid)
{
	printerror('ErrorUrl','');
}
$addecmscheck='';
if($index_infor['checked'])
{
	$addecmscheck='&ecmscheck=1';
}
//审核
if(empty($index_infor['checked']))
{
	$checked=" title='未审核' style='background:#99C4E3'";
	$titleurl="../ShowInfo.php?classid=".$infor['classid']."&id=".$infor['id'].$addecmscheck.$ecms_hashur['ehref'];
	$eagotourl=$infor['ismember']?$titleurl:eapage_hGetGotoUrl($titleurl,'',$infor['classid'],$infor['id'],'eaShowInfo',0);
}
else
{
	$checked="";
	$titleurl=sys_ReturnBqTitleLink($infor);
	$eagotourl=eapage_hGetGotoUrl($titleurl,'',$infor['classid'],$infor['id'],'eaShowInfoUrl',0);
}
$eagotourl_onclick='';
if($eagotourl!=$titleurl)
{
	$eagotourl_onclick=' onclick="window.open(\''.$eagotourl.'\');return false;"';
}
//系统模型
$mr=$empire->fetch1("select mid,mname,usemod from {$dbtbpre}enewsmod where mid='".$modid."'");
//取得栏目名
$do=$infor['classid'];
$dob=$class_r[$infor['classid']]['bclassid'];

$postword='增加父信息';
$url="<a href=ListFzinfo.php".$ecms_hashur['whehref'].">管理父信息</a> &gt; 增加父信息：<b>".$infor['title']."</b>";
$fcid=(int)$_GET['fcid'];
eCheckStrType(4,$enews,1);
if($enews=='EditFzinfo')
{
	$enews='EditFzinfo';
}
else
{
	$enews='AddFzinfo';
}
//formhash
$efh=heformhash_get($enews);
//修改
if($enews=="EditFzinfo")
{
	$postword='修改父信息';
	$r=$empire->fetch1("select * from {$dbtbpre}enewsfz_info where pubid='$pubid'".do_dblimit_one());
	$url="<a href=ListFzinfo.php".$ecms_hashur['whehref'].">管理父信息</a> -&gt; 修改父信息：<b>".$infor['title']."</b>";
}
//栏目
$options=ShowClass_AddClass("",$r['sclassid'],0,"|-",0,0);
//分类
$cs='';
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsfz_infoclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select='';
	if($r['cid']==$cr['classid'])
	{
		$select=' selected';
	}
	$cs.="<option value='".$cr['classid']."'".$select.">".$cr['classname']."</option>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>父信息设置</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?></td>
  </tr>
</table>
<form name="form1" method="post" action="ecmsfzinfo.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"><?=$postword?> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
		<input name="classid" type="hidden" id="classid" value="<?=$classid?>">
		<input name="id" type="hidden" id="id" value="<?=$id?>">
        <input name="fcid" type="hidden" id="fcid" value="<?=$fcid?>"> </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width="20%" height="25">父信息ID：</td>
      <td width="80%" height="25"><?=$id?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">父信息公共ID：</td>
      <td height="25"><?=$pubid?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">父信息标题：</td>
      <td height="25"><a href="<?=$titleurl?>"<?=$eagotourl_onclick?> target="_blank"><?=$infor['title']?></a></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">父信息所属栏目：</td>
      <td height="25">
	  <a href='../ListNews.php?bclassid=<?=$class_r[$infor['classid']]['bclassid']?>&classid=<?=$infor['classid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>' target="_blank"><?=$class_r[$dob]['classname']?></a> &gt; <a href='../ListNews.php?bclassid=<?=$class_r[$infor['classid']]['bclassid']?>&classid=<?=$infor['classid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>' target="_blank"><?=$class_r[$infor['classid']]['classname']?></a>	  </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">所属系统模型：</td>
      <td height="25"><?=$mr['mname']?></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">所属父信息分类：</td>
      <td height="25"><select name="cid" id="cid">
          <option value="0">不分类</option>
		  <?=$cs?>
        </select> 
        <input type="button" name="Submit62223" value="管理分类" onClick="window.open('FzinfoClass.php<?=$ecms_hashur['whehref']?>');"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">隶属信息栏目：</td>
      <td height="25">
	  <select name="sclassid" id="sclassid">
          <option value="0">隶属于所有栏目</option>
          <?=$options?>
        </select> <input type="button" name="Submit622232" value="管理栏目" onClick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>');"> 
        <font color="#666666">(选择父栏目，将应用于子栏目)</font>	  </td>
    </tr>
	<tr bgcolor="#FFFFFF">
      <td height="25">开启前台投稿：</td>
      <td height="25">
        <input name="qadd" type="checkbox" id="qadd" value="1"<?=$r['qadd']==1?' checked':''?>>
      开启<font color="#666666"> (投稿地址：/e/DoInfo/AddInfo.php?mid=系统模型ID&amp;classid=栏目ID&amp;fztid=父信息表ID&amp;fzid=父信息ID&amp;fzcid=子信息分类ID)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">信息可选择：</td>
      <td height="25">
	  <input type="radio" name="usefz" value="0"<?=$r['usefz']==0?' checked':''?>>
        是 
        <input type="radio" name="usefz" value="1"<?=$r['usefz']==1?' checked':''?>>
        否<font color="#666666">（如果选择否那么选择父信息时不会显示这个父信息）</font>	  </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"> <input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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