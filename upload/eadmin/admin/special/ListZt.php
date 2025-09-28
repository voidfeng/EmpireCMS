<?php
define('EmpireCMSAdmin','1');
require('../../../e/class/connect.php');
require('../../../e/class/functions.php');
require '../../../e/data/'.LoadLang('pub/fun.php');
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
CheckLevel($logininid,$loginin,$classid,"zt");

//修改栏目顺序
function EditZtOrder($ztid,$myorder,$userid,$username){
	global $empire,$dbtbpre;
	$ztid=eCheckEmptyArray($ztid);
	for($i=0;$i<count($ztid);$i++)
	{
		$newmyorder=(int)$myorder[$i];
		$ztid[$i]=(int)$ztid[$i];
		$sql=$empire->query("update {$dbtbpre}enewszt set myorder='$newmyorder' where ztid='".$ztid[$i]."'");
    }
	//操作日志
	insert_dolog("");
	printerror("EditZtOrderSuccess",EcmsGetReturnUrl());
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//修改显示顺序
if($enews=="EditZtOrder")
{
	EditZtOrder($_POST['ztid'],$_POST['myorder'],$logininid,$loginin);
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add="";
$search="";
$search.=$ecms_hashur['ehref'];
$url="<a href=ListZt.php".$ecms_hashur['whehref'].">管理专题</a>";
//类别
$where=' where ';
$zcid=(int)$_GET['zcid'];
if($zcid)
{
	$add=$where."zcid='$zcid'";
	$search.="&zcid=$zcid";
	$where=' and ';
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
		$show=(int)$_GET['show'];
		if($show==1)
		{
			$add.=$where."(ztname like '%$keyboard%')";
		}
		elseif($show==2)
		{
			$add.=$where."(intro like '%$keyboard%')";
		}
		elseif($show==3)
		{
			$add.=$where."(ztid='$keyboard')";
		}
		elseif($show==4)
		{
			$add.=$where."(ztpath like '%$keyboard%')";
		}
		else
		{
			$add.=$where."(ztname like '%$keyboard%' or intro like '%$keyboard%' or ztpath like '%$keyboard%'".($ecms_config['db']['usedb']=='pgsql'?"":" or ztid='$keyboard'").")";
		}
		$search.="&keyboard=$keyboard&show=$show";
		$where=' and ';
	}
	//条件
	$scond=(int)$_GET['scond'];
	if($scond)
	{
		if($scond==1)
		{
			$add.=$where."islist=1";
		}
		elseif($scond==2)
		{
			$add.=$where."islist=0";
		}
		elseif($scond==3)
		{
			$add.=$where."islist=2";
		}
		elseif($scond==4)
		{
			$add.=$where."closepl=0";
		}
		elseif($scond==5)
		{
			$add.=$where."closepl=1";
		}
		$search.="&scond=$scond";
	}
}
//分类
$zcstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsztclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr['classid']==$zcid)
	{
		$select=" selected";
	}
	$zcstr.="<option value='".$cr['classid']."'".$select.">".$cr['classname']."</option>";
}
$totalquery="select count(*) as total from {$dbtbpre}enewszt".$add;
$query="select * from {$dbtbpre}enewszt".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by myorder,ztid desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);

//formhash
$efhr=heformhash_getr('EditZtOrder');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>专题</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit52" value="增加专题" onclick="self.location.href='AddZt.php?enews=AddZt<?=$ecms_hashur['ehref']?>';"> 
		&nbsp;&nbsp;
        <input type="button" name="Submit6" value="数据更新中心" onclick="window.open('../ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>');">
      </div></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <form name="form1" method="get" action="ListZt.php">
  <?=$ecms_hashur['eform']?>
    <tr> 
      <td height="30">限制显示： 
        <select name="zcid" id="zcid">
          <option value="0">不限分类</option>
          <?=$zcstr?>
        </select>
        <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <select name="show" id="show">
          <option value="0"<?=$show==0?' selected':''?>>不限字段</option>
          <option value="1"<?=$show==1?' selected':''?>>专题名</option>
          <option value="2"<?=$show==2?' selected':''?>>专题简介</option>
          <option value="3"<?=$show==3?' selected':''?>>专题ID</option>
          <option value="4"<?=$show==4?' selected':''?>>专题目录</option>
        </select>
        <select name="scond" id="scond">
          <option value="0"<?=$scond==0?' selected':''?>>不限条件</option>
          <option value="1"<?=$scond==1?' selected':''?>>列表式</option>
          <option value="2"<?=$scond==2?' selected':''?>>封面式</option>
          <option value="3"<?=$scond==3?' selected':''?>>页面内容式</option>
		  <option value="4"<?=$scond==4?' selected':''?>>开放评论的专题</option>
		  <option value="5"<?=$scond==5?' selected':''?>>关闭评论的专题</option>
        </select>
      <input type="submit" name="Submit8" value="显示">
      <input name="sear" type="hidden" id="sear" value="1"></td>
    </tr>
  </form>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="editorder" method="post" action="ListZt.php">
  <?=$ecms_hashur['form']?>
  <?=$efhr['vform']?>
    <tr class="header"> 
      <td width="5%"><div align="center">顺序</div></td>
      <td width="6%" height="25"><div align="center">ID</div></td>
      <td width="34%" height="25"><div align="center">专题名</div></td>
      <td width="20%"><div align="center">增加时间</div></td>
      <td width="11%"><div align="center">访问量</div></td>
      <td width="13%">专题管理</td>
      <td width="11%" height="25"><div align="center">操作</div></td>
    </tr>
    <?php
  //formhash
  $efh=heformhash_get('ReZtHtml',1);
  $efh1=heformhash_get('DelZt',1);
	
  while($r=$empire->fetch($sql))
  {
  if($r['zturl'])
  {
  	$ztlink=$r['zturl'];
  }
  else
  {
  	$ztlink="../../../".$r['ztpath'];
  }
  $eagotourl=eapage_hGetGotoUrl($ztlink,'../',$r['ztid'],0,'eaGoUrlZt',0);
  $eagotourl_onclick='';
  if($eagotourl!=$ztlink)
  {
	  $eagotourl_onclick=' onclick="window.open(\''.$eagotourl.'\');return false;"';
  }
  ?>
    <tr bgcolor="ffffff" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="myorder[]" type="text" id="myorder[]" value="<?=$r['myorder']?>" size="2">
          <input name="ztid[]" type="hidden" id="ztid[]" value="<?=$r['ztid']?>">
        </div></td>
      <td height="25"><div align="center"> 
          <a href="../ecmschtml.php?enews=ReZtHtml&ztid=<?=$r['ztid']?><?=$ecms_hashur['href'].$efh?>"><?=$r['ztid']?></a>
        </div></td>
      <td height="25"><div align="center"> 
          <a href="<?=$ztlink?>"<?=$eagotourl_onclick?> target="_blank"><?=$r['ztname']?></a>
        </div></td>
      <td><div align="center"><?=$r['addtime']?date("Y-m-d",$r['addtime']):'---'?></div></td>
      <td><div align="center"> 
          <?=$r['onclick']?>
        </div></td>
      <td><a href="AddZt.php?enews=EditZt&ztid=<?=$r['ztid']?><?=$ecms_hashur['ehref']?>">修改</a> <a href="../ecmschtml.php?enews=ReZtHtml&ztid=<?=$r['ztid']?>&ecms=1<?=$ecms_hashur['href'].$efh?>">刷新</a> <a href="AddZt.php?enews=AddZt&ztid=<?=$r['ztid']?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a> <a href="../ecmsclass.php?enews=DelZt&ztid=<?=$r['ztid']?><?=$ecms_hashur['href'].$efh1?>" onclick="return confirm('确认要删除此专题？');">删除</a></td>
      <td height="25"><div align="center"><a href="#ecms" onclick="window.open('../openpage/AdminPage.php?efileid=8&getclassid=<?=$r['ztid']?>&getclassname=<?=urlencode($r['ztname'])?><?=$ecms_hashur['ehref']?>','','');">更新专题</a></div></td>
    </tr>
    <?php
  }
  ?>
    <tr bgcolor="ffffff"> 
      <td height="25" colspan="7"><div align="right">
        <input type="submit" name="Submit5" value="修改专题顺序" onClick="document.editorder.enews.value='EditZtOrder';document.editorder.<?=$efhr['vname']?>.value='<?=$efhr['vval']?>';"> 
        <input name="enews" type="hidden" id="enews" value="EditZtOrder"> 
      <font color="#666666">(顺序值越小越前面)</font></div></td>
    </tr>
    <tr bgcolor="ffffff">
      <td height="25" colspan="7">&nbsp;&nbsp; 
        <?=$returnpage?></td>
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
